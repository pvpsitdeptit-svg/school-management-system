<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'school_id' => school_id(),
        'school' => school() ? [
            'id' => school()->id,
            'name' => school()->name,
            'code' => school()->code,
        ] : null,
    ]);
});

// Auth endpoints
Route::get('/me', function (Request $request) {
    $user = $request->get('authenticated_user');
    
    if (!$user) {
        return response()->json([
            'error' => 'Not authenticated',
            'message' => 'Please provide a valid Firebase token'
        ], 401);
    }

    return response()->json([
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'firebase_uid' => $user->firebase_uid,
        ],
        'school' => $user->school ? [
            'id' => $user->school->id,
            'name' => $user->school->name,
            'code' => $user->school->code,
            'subdomain' => $user->school->subdomain,
        ] : null,
        'permissions' => [
            'can_manage_school' => in_array($user->role, ['super_admin', 'admin']),
            'can_teach' => $user->role === 'faculty',
            'can_view_own_data' => in_array($user->role, ['student', 'parent']),
        ]
    ]);
})->middleware('auth.firebase');

// Protected routes by role
Route::middleware(['auth.firebase', 'admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return response()->json([
            'message' => 'Admin dashboard',
            'data' => [
                'total_users' => User::count(),
                'school_users' => User::where('school_id', school_id())->count(),
            ]
        ]);
    });
    
    Route::get('/admin/users', function () {
        return response()->json([
            'users' => User::where('school_id', school_id())
                ->select('id', 'name', 'email', 'role', 'status')
                ->get()
        ]);
    });
});

Route::middleware(['auth.firebase', 'faculty'])->group(function () {
    Route::get('/faculty/dashboard', function () {
        return response()->json([
            'message' => 'Faculty dashboard',
            'data' => [
                'classes' => \App\Models\SchoolClass::where('school_id', school_id())->count(),
                'subjects' => \App\Models\Subject::where('school_id', school_id())->count(),
            ]
        ]);
    });
    
    // Mark attendance for a class
    Route::post('/faculty/attendance', function (Request $request) {
        $user = $request->get('authenticated_user');
        
        // Validate request
        $validated = $request->validate([
            'class_id' => 'required|integer',
            'date' => 'required|date|before_or_equal:today',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|integer',
            'attendance.*.status' => 'required|in:present,absent',
        ]);
        
        // Verify class belongs to the same school
        $class = SchoolClass::where('id', $validated['class_id'])
            ->where('school_id', school_id())
            ->first();
            
        if (!$class) {
            return response()->json([
                'error' => 'Invalid class',
                'message' => 'Class not found or does not belong to your school'
            ], 404);
        }
        
        // Check for existing attendance before saving
        $studentIds = collect($validated['attendance'])->pluck('student_id')->toArray();
        
        $existingAttendance = Attendance::where('school_id', school_id())
            ->where('class_id', $validated['class_id'])
            ->whereDate('date', $validated['date']) // Use whereDate for date comparison
            ->whereIn('student_id', $studentIds)
            ->pluck('student_id')
            ->toArray();
        
        if (!empty($existingAttendance)) {
            return response()->json([
                'error' => 'Attendance already marked',
                'message' => 'Attendance already exists for ' . count($existingAttendance) . ' student(s) on this date',
                'duplicate_students' => $existingAttendance
            ], 409); // 409 Conflict
        }
        
        // Get all students in this class
        $classStudents = Student::where('class_id', $validated['class_id'])
            ->where('school_id', school_id())
            ->pluck('id')
            ->toArray();
        
        $attendanceRecords = [];
        $errors = [];
        
        foreach ($validated['attendance'] as $attendanceData) {
            $studentId = $attendanceData['student_id'];
            
            // Verify student belongs to this class and school
            if (!in_array($studentId, $classStudents)) {
                $errors[] = "Student ID {$studentId} does not belong to this class";
                continue;
            }
            
            try {
                // Create or update attendance record
                Attendance::updateOrCreate(
                    [
                        'school_id' => school_id(),
                        'student_id' => $studentId,
                        'date' => $validated['date'],
                    ],
                    [
                        'class_id' => $validated['class_id'],
                        'status' => $attendanceData['status'],
                        'marked_by' => $user->id,
                    ]
                );
                
                $attendanceRecords[] = [
                    'student_id' => $studentId,
                    'status' => $attendanceData['status']
                ];
                
            } catch (\Exception $e) {
                $errors[] = "Failed to save attendance for student ID {$studentId}: " . $e->getMessage();
            }
        }
        
        if (!empty($errors)) {
            return response()->json([
                'message' => 'Attendance saved with some errors',
                'saved_records' => count($attendanceRecords),
                'errors' => $errors
            ], 207); // 207 Multi-Status
        }
        
        return response()->json([
            'message' => 'Attendance saved successfully',
            'saved_records' => count($attendanceRecords),
            'attendance' => $attendanceRecords
        ]);
    });
    
    // View attendance for a specific class and date
    Route::get('/faculty/attendance', function (Request $request) {
        $validated = $request->validate([
            'class_id' => 'required|integer',
            'date' => 'required|date',
        ]);
        
        // Verify class belongs to the same school
        $class = SchoolClass::where('id', $validated['class_id'])
            ->where('school_id', school_id())
            ->first();
            
        if (!$class) {
            return response()->json([
                'error' => 'Invalid class',
                'message' => 'Class not found or does not belong to your school'
            ], 404);
        }
        
        $attendance = Attendance::with(['student.user'])
            ->where('school_id', school_id())
            ->where('class_id', $validated['class_id'])
            ->where('date', $validated['date'])
            ->get()
            ->map(function ($record) {
                return [
                    'student_id' => $record->student_id,
                    'student_name' => $record->student->user->name,
                    'status' => $record->status,
                    'marked_by' => $record->markedByUser->name,
                    'created_at' => $record->created_at,
                ];
            });
        
        return response()->json([
            'class_id' => $validated['class_id'],
            'date' => $validated['date'],
            'attendance' => $attendance
        ]);
    });
});

Route::middleware(['auth.firebase', 'student.or.parent'])->group(function () {
    Route::get('/student/profile', function (Request $request) {
        $user = $request->get('authenticated_user');
        
        return response()->json([
            'message' => 'Student/Parent profile',
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ]);
    });
    
    // View attendance for student/parent
    Route::get('/student/attendance', function (Request $request) {
        $user = $request->get('authenticated_user');
        
        // Validate request
        $validated = $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);
        
        // Get student(s) based on user role
        $studentIds = [];
        
        if ($user->role === 'student') {
            // Student can only see their own attendance
            $student = Student::where('user_id', $user->id)
                ->where('school_id', school_id())
                ->first();
            
            if (!$student) {
                return response()->json([
                    'error' => 'Student profile not found',
                    'message' => 'No student profile found for this user'
                ], 404);
            }
            
            $studentIds = [$student->id];
        } elseif ($user->role === 'parent') {
            // Parent can see their children's attendance
            // For now, we'll assume parent-student relationship is stored in students table
            // In a real system, you'd have a separate parent_student_relationship table
            $students = Student::where('school_id', school_id())
                ->where('parent_user_id', $user->id) // This field needs to be added to students table
                ->pluck('id')
                ->toArray();
            
            if (empty($students)) {
                return response()->json([
                    'error' => 'No children found',
                    'message' => 'No students linked to this parent account'
                ], 404);
            }
            
            $studentIds = $students;
        }
        
        // Calculate date range for the month
        $startDate = sprintf('%04d-%02d-01', $validated['year'], $validated['month']);
        $endDate = date('Y-m-t', strtotime($startDate));
        
        // Get attendance records
        $attendanceRecords = Attendance::with(['student.user', 'student.schoolClass'])
            ->whereIn('student_id', $studentIds)
            ->where('school_id', school_id())
            ->whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('student_id')
            ->get();
        
        // Calculate statistics
        $totalDays = $attendanceRecords->count();
        $presentDays = $attendanceRecords->where('status', 'present')->count();
        $absentDays = $attendanceRecords->where('status', 'absent')->count();
        
        // Group by student for response
        $attendanceByStudent = [];
        foreach ($attendanceRecords as $record) {
            $studentId = $record->student_id;
            
            if (!isset($attendanceByStudent[$studentId])) {
                $attendanceByStudent[$studentId] = [
                    'student_id' => $studentId,
                    'student_name' => $record->student->user->name,
                    'class_name' => $record->student->schoolClass->name . ' ' . $record->student->schoolClass->section,
                    'total_days' => 0,
                    'present' => 0,
                    'absent' => 0,
                    'records' => []
                ];
            }
            
            $attendanceByStudent[$studentId]['total_days']++;
            $attendanceByStudent[$studentId][$record->status]++;
            $attendanceByStudent[$studentId]['records'][] = [
                'date' => $record->date,
                'status' => $record->status
            ];
        }
        
        return response()->json([
            'month' => $validated['month'],
            'year' => $validated['year'],
            'total_students' => count($attendanceByStudent),
            'summary' => [
                'total_days' => $totalDays,
                'present' => $presentDays,
                'absent' => $absentDays,
                'attendance_percentage' => $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0
            ],
            'students' => array_values($attendanceByStudent)
        ]);
    });
});

// API routes will be added here
// All routes will have school detection middleware applied

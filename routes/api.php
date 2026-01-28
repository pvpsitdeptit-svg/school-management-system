<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\Mark;
use App\Models\Subject;
use App\Models\FacultySubject;
use App\Models\DeviceToken;

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
                'total_students' => User::where('school_id', school_id())->where('role', 'student')->count(),
                'total_faculty' => User::where('school_id', school_id())->where('role', 'faculty')->count(),
                'total_classes' => SchoolClass::where('school_id', school_id())->count(),
                'total_subjects' => Subject::where('school_id', school_id())->count(),
                'total_assignments' => FacultySubject::where('school_id', school_id())->count(),
                'active_classes' => SchoolClass::where('school_id', school_id())->where('status', 'active')->count(),
                'active_students' => Student::where('school_id', school_id())->where('status', 'active')->count(),
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
    
    // Create new exam
    Route::post('/admin/exams', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'exam_date' => 'nullable|date',
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
        
        $exam = Exam::create([
            'school_id' => school_id(),
            'class_id' => $validated['class_id'],
            'name' => $validated['name'],
            'exam_date' => $validated['exam_date'],
            'status' => 'draft',
        ]);
        
        return response()->json([
            'message' => 'Exam created successfully',
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'class_id' => $exam->class_id,
                'status' => $exam->status,
                'exam_date' => $exam->exam_date,
            ]
        ], 201);
    });
    
    // Add subjects to exam
    Route::post('/admin/exams/{id}/subjects', function (Request $request, $id) {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*.subject_id' => 'required|integer',
            'subjects.*.max_marks' => 'required|integer|min:1',
        ]);
        
        // Verify exam belongs to the same school
        $exam = Exam::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$exam) {
            return response()->json([
                'error' => 'Invalid exam',
                'message' => 'Exam not found or does not belong to your school'
            ], 404);
        }
        
        // Prevent adding subjects to published exams
        if ($exam->status === 'published') {
            return response()->json([
                'error' => 'Exam published',
                'message' => 'Cannot add subjects to published exams'
            ], 403);
        }
        
        $examSubjects = [];
        foreach ($validated['subjects'] as $subjectData) {
            $examSubject = ExamSubject::create([
                'exam_id' => $exam->id,
                'subject_id' => $subjectData['subject_id'],
                'max_marks' => $subjectData['max_marks'],
            ]);
            
            $examSubjects[] = [
                'id' => $examSubject->id,
                'subject_id' => $examSubject->subject_id,
                'max_marks' => $examSubject->max_marks,
            ];
        }
        
        return response()->json([
            'message' => 'Subjects added to exam successfully',
            'exam_id' => $exam->id,
            'subjects' => $examSubjects
        ]);
    });
    
    // Publish exam
    Route::post('/admin/exams/{id}/publish', function ($id) {
        // Verify exam belongs to the same school
        $exam = Exam::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$exam) {
            return response()->json([
                'error' => 'Invalid exam',
                'message' => 'Exam not found or does not belong to your school'
            ], 404);
        }
        
        // Check if exam has subjects
        if ($exam->examSubjects->count() === 0) {
            return response()->json([
                'error' => 'No subjects',
                'message' => 'Cannot publish exam without subjects'
            ], 400);
        }
        
        $exam->update(['status' => 'published']);
        
        return response()->json([
            'message' => 'Exam published successfully',
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'status' => $exam->status,
            ]
        ]);
    });
    
    // Class Management APIs
    Route::post('/admin/classes', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'required|string|max:10',
            'status' => 'sometimes|in:active,inactive',
        ]);
        
        // Check for duplicate class in same school
        $existingClass = SchoolClass::where('school_id', school_id())
            ->where('name', $validated['name'])
            ->where('section', $validated['section'])
            ->first();
            
        if ($existingClass) {
            return response()->json([
                'error' => 'Duplicate class',
                'message' => "Class {$validated['name']}-{$validated['section']} already exists"
            ], 409);
        }
        
        $class = SchoolClass::create([
            'school_id' => school_id(),
            'name' => $validated['name'],
            'section' => $validated['section'],
            'status' => $validated['status'] ?? 'active',
        ]);
        
        return response()->json([
            'message' => 'Class created successfully',
            'class' => [
                'id' => $class->id,
                'name' => $class->name,
                'section' => $class->section,
                'status' => $class->status,
            ]
        ], 201);
    });
    
    Route::get('/admin/classes', function () {
        $classes = SchoolClass::where('school_id', school_id())
            ->orderBy('name')
            ->orderBy('section')
            ->get()
            ->map(function ($class) {
                return [
                    'id' => $class->id,
                    'name' => $class->name,
                    'section' => $class->section,
                    'status' => $class->status,
                    'students_count' => Student::where('class_id', $class->id)->where('school_id', school_id())->count(),
                ];
            });
        
        return response()->json([
            'classes' => $classes,
            'total_classes' => $classes->count()
        ]);
    });
    
    Route::put('/admin/classes/{id}', function (Request $request, $id) {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'section' => 'sometimes|required|string|max:10',
            'status' => 'sometimes|required|in:active,inactive',
        ]);
        
        $class = SchoolClass::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$class) {
            return response()->json([
                'error' => 'Class not found',
                'message' => 'Class not found or does not belong to your school'
            ], 404);
        }
        
        // Check for duplicate if name/section is being updated
        if (isset($validated['name']) || isset($validated['section'])) {
            $name = $validated['name'] ?? $class->name;
            $section = $validated['section'] ?? $class->section;
            
            $duplicate = SchoolClass::where('school_id', school_id())
                ->where('name', $name)
                ->where('section', $section)
                ->where('id', '!=', $class->id)
                ->first();
                
            if ($duplicate) {
                return response()->json([
                    'error' => 'Duplicate class',
                    'message' => "Class {$name}-{$section} already exists"
                ], 409);
            }
        }
        
        $class->update($validated);
        
        return response()->json([
            'message' => 'Class updated successfully',
            'class' => [
                'id' => $class->id,
                'name' => $class->name,
                'section' => $class->section,
                'status' => $class->status,
            ]
        ]);
    });
    
    Route::delete('/admin/classes/{id}', function ($id) {
        $class = SchoolClass::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$class) {
            return response()->json([
                'error' => 'Class not found',
                'message' => 'Class not found or does not belong to your school'
            ], 404);
        }
        
        // Check if class has students
        if (Student::where('class_id', $class->id)->where('school_id', school_id())->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete',
                'message' => 'Cannot delete class with enrolled students'
            ], 400);
        }
        
        $class->delete();
        
        return response()->json([
            'message' => 'Class deleted successfully'
        ]);
    });
    
    // Subject Master APIs
    Route::post('/admin/subjects', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:10',
        ]);
        
        // Check for duplicate subject in same school
        $existingSubject = Subject::where('school_id', school_id())
            ->where('name', $validated['name'])
            ->first();
            
        if ($existingSubject) {
            return response()->json([
                'error' => 'Duplicate subject',
                'message' => "Subject '{$validated['name']}' already exists"
            ], 409);
        }
        
        $subject = Subject::create([
            'school_id' => school_id(),
            'name' => $validated['name'],
            'code' => $validated['code'] ?? null,
        ]);
        
        return response()->json([
            'message' => 'Subject created successfully',
            'subject' => [
                'id' => $subject->id,
                'name' => $subject->name,
                'code' => $subject->code,
            ]
        ], 201);
    });
    
    Route::get('/admin/subjects', function () {
        $subjects = Subject::where('school_id', school_id())
            ->orderBy('name')
            ->get()
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'name' => $subject->name,
                    'code' => $subject->code,
                    'exam_subjects_count' => $subject->examSubjects()->count(),
                ];
            });
        
        return response()->json([
            'subjects' => $subjects,
            'total_subjects' => $subjects->count()
        ]);
    });
    
    Route::delete('/admin/subjects/{id}', function ($id) {
        $subject = Subject::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$subject) {
            return response()->json([
                'error' => 'Subject not found',
                'message' => 'Subject not found or does not belong to your school'
            ], 404);
        }
        
        // Check if subject is in use
        if ($subject->examSubjects()->count() > 0) {
            return response()->json([
                'error' => 'Cannot delete',
                'message' => 'Cannot delete subject that is used in exams'
            ], 400);
        }
        
        // Check if subject is assigned to faculty
        $facultyAssignments = FacultySubject::where('school_id', school_id())
            ->where('subject_id', $id)
            ->count();
            
        if ($facultyAssignments > 0) {
            return response()->json([
                'error' => 'Cannot delete',
                'message' => 'Cannot delete subject that is assigned to faculty'
            ], 400);
        }
        
        $subject->delete();
        
        return response()->json([
            'message' => 'Subject deleted successfully'
        ]);
    });
    
    Route::delete('/admin/faculty/assignments/{id}', function ($id) {
        $assignment = FacultySubject::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$assignment) {
            return response()->json([
                'error' => 'Assignment not found',
                'message' => 'Faculty assignment not found or does not belong to your school'
            ], 404);
        }
        
        $assignment->delete();
        
        return response()->json([
            'message' => 'Faculty assignment deleted successfully'
        ]);
    });
    
    // Faculty Assignment APIs
    Route::post('/admin/faculty/assign', function (Request $request) {
        $validated = $request->validate([
            'faculty_user_id' => 'required|integer',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
        ]);
        
        // Verify faculty belongs to the same school
        $faculty = User::where('id', $validated['faculty_user_id'])
            ->where('school_id', school_id())
            ->where('role', 'faculty')
            ->first();
            
        if (!$faculty) {
            return response()->json([
                'error' => 'Invalid faculty',
                'message' => 'Faculty not found or does not belong to your school'
            ], 404);
        }
        
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
        
        // Verify subject belongs to the same school
        $subject = Subject::where('id', $validated['subject_id'])
            ->where('school_id', school_id())
            ->first();
            
        if (!$subject) {
            return response()->json([
                'error' => 'Invalid subject',
                'message' => 'Subject not found or does not belong to your school'
            ], 404);
        }
        
        // Check for duplicate assignment
        $existingAssignment = FacultySubject::where('faculty_user_id', $validated['faculty_user_id'])
            ->where('class_id', $validated['class_id'])
            ->where('subject_id', $validated['subject_id'])
            ->first();
            
        if ($existingAssignment) {
            return response()->json([
                'error' => 'Duplicate assignment',
                'message' => 'Faculty is already assigned to this subject for this class'
            ], 409);
        }
        
        $assignment = FacultySubject::create([
            'school_id' => school_id(),
            'faculty_user_id' => $validated['faculty_user_id'],
            'class_id' => $validated['class_id'],
            'subject_id' => $validated['subject_id'],
        ]);
        
        return response()->json([
            'message' => 'Faculty assigned successfully',
            'assignment' => [
                'id' => $assignment->id,
                'faculty_name' => $faculty->name,
                'class_name' => $class->name . ' ' . $class->section,
                'subject_name' => $subject->name,
            ]
        ], 201);
    });
    
    Route::get('/admin/faculty/assignments', function () {
        $assignments = FacultySubject::with(['faculty', 'schoolClass', 'subject'])
            ->where('school_id', school_id())
            ->orderBy('faculty_user_id')
            ->orderBy('class_id')
            ->orderBy('subject_id')
            ->get()
            ->map(function ($assignment) {
                return [
                    'id' => $assignment->id,
                    'faculty' => [
                        'id' => $assignment->faculty->id,
                        'name' => $assignment->faculty->name,
                        'email' => $assignment->faculty->email,
                    ],
                    'class' => [
                        'id' => $assignment->schoolClass->id,
                        'name' => $assignment->schoolClass->name,
                        'section' => $assignment->schoolClass->section,
                    ],
                    'subject' => [
                        'id' => $assignment->subject->id,
                        'name' => $assignment->subject->name,
                        'code' => $assignment->subject->code,
                    ],
                ];
            });
        
        return response()->json([
            'assignments' => $assignments,
            'total_assignments' => $assignments->count()
        ]);
    });
    
    // Student Admission APIs
    Route::post('/admin/students', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'admission_no' => 'required|string|max:50',
            'class_id' => 'required|integer',
            'status' => 'sometimes|in:active,inactive',
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
        
        // Check for duplicate email
        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser) {
            return response()->json([
                'error' => 'Duplicate email',
                'message' => 'Email already exists'
            ], 409);
        }
        
        // Check for duplicate admission number in same school
        $existingStudent = Student::where('school_id', school_id())
            ->where('admission_no', $validated['admission_no'])
            ->first();
            
        if ($existingStudent) {
            return response()->json([
                'error' => 'Duplicate admission number',
                'message' => "Admission number {$validated['admission_no']} already exists"
            ], 409);
        }
        
        // Create user account
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'student',
            'status' => 'active',
            'school_id' => school_id(),
            'firebase_uid' => 'temp_' . uniqid(), // Temporary UID, will be updated later
        ]);
        
        // Create student profile
        $student = Student::create([
            'school_id' => school_id(),
            'user_id' => $user->id,
            'admission_no' => $validated['admission_no'],
            'status' => $validated['status'] ?? 'active',
        ]);
        
        return response()->json([
            'message' => 'Student admitted successfully',
            'student' => [
                'id' => $student->id,
                'user_id' => $user->id,
                'admission_no' => $student->admission_no,
                'name' => $user->name,
                'email' => $user->email,
                'class' => $class->name . ' ' . $class->section,
                'status' => $student->status,
            ]
        ], 201);
    });
    
    Route::get('/admin/students', function (Request $request) {
        $query = Student::with(['user', 'schoolClass'])
            ->where('school_id', school_id());
            
        // Filter by class if provided
        if ($request->has('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        $students = $query->orderBy('admission_no')
            ->get()
            ->map(function ($student) {
                return [
                    'id' => $student->id,
                    'user_id' => $student->user_id,
                    'admission_no' => $student->admission_no,
                    'name' => $student->user->name,
                    'email' => $student->user->email,
                    'class' => [
                        'id' => $student->schoolClass->id,
                        'name' => $student->schoolClass->name,
                        'section' => $student->schoolClass->section,
                    ],
                    'status' => $student->status,
                    'created_at' => $student->created_at->toISOString(),
                ];
            });
        
        return response()->json([
            'students' => $students,
            'total_students' => $students->count()
        ]);
    });
    
    // Publish Exam Results with Notifications
    Route::post('/admin/exams/{id}/publish', function ($id) {
        $exam = Exam::where('id', $id)
            ->where('school_id', school_id())
            ->first();
            
        if (!$exam) {
            return response()->json([
                'error' => 'Exam not found',
                'message' => 'Exam not found or does not belong to your school'
            ], 404);
        }
        
        if ($exam->status === 'published') {
            return response()->json([
                'error' => 'Already published',
                'message' => 'Exam results are already published'
            ], 400);
        }
        
        // Update exam status to published
        $exam->update(['status' => 'published']);
        
        // Get all students in the exam's class
        $examSubjects = $exam->examSubjects()->with('subject')->get();
        $studentIds = [];
        
        foreach ($examSubjects as $examSubject) {
            $marks = Mark::where('exam_id', $exam->id)
                ->where('subject_id', $examSubject->subject_id)
                ->where('school_id', school_id())
                ->get();
                
            foreach ($marks as $mark) {
                $studentIds[] = $mark->student_id;
            }
        }
        
        // Remove duplicates and get unique student IDs
        $uniqueStudentIds = array_unique($studentIds);
        
        // Send notifications to all students
        if (!empty($uniqueStudentIds)) {
            $fcmService = new \App\Services\FcmNotificationService();
            $fcmService->sendExamResultsNotification($uniqueStudentIds, $exam->name);
        }
        
        return response()->json([
            'message' => 'Exam results published successfully',
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'status' => $exam->status,
                'notified_students' => count($uniqueStudentIds)
            ]
        ]);
    });
});

// Faculty Routes
Route::middleware(['auth.firebase', 'faculty'])->group(function () {
    // Mark Attendance API with Notification
    Route::post('/faculty/attendance', function (Request $request) {
        $validated = $request->validate([
            'class_id' => 'required|integer',
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|integer',
            'attendances.*.status' => 'required|in:present,absent,leave'
        ]);
        
        // Verify faculty belongs to the same school
        $faculty = auth()->user();
        
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
        
        $attendanceRecords = [];
        $studentsToNotify = [];
        
        foreach ($validated['attendances'] as $attendanceData) {
            // Verify student belongs to the same school and class
            $student = Student::where('id', $attendanceData['student_id'])
                ->where('class_id', $validated['class_id'])
                ->where('school_id', school_id())
                ->first();
                
            if (!$student) {
                return response()->json([
                    'error' => 'Invalid student',
                    'message' => "Student ID {$attendanceData['student_id']} not found in this class"
                ], 404);
            }
            
            // Check if attendance already exists
            $existing = Attendance::where('student_id', $attendanceData['student_id'])
                ->where('date', $validated['date'])
                ->where('school_id', school_id())
                ->first();
                
            if ($existing) {
                // Update existing attendance
                $existing->update([
                    'status' => $attendanceData['status'],
                    'marked_by' => $faculty->id
                ]);
                $attendanceRecords[] = $existing;
            } else {
                // Create new attendance record
                $attendance = Attendance::create([
                    'school_id' => school_id(),
                    'student_id' => $attendanceData['student_id'],
                    'class_id' => $validated['class_id'],
                    'date' => $validated['date'],
                    'status' => $attendanceData['status'],
                    'marked_by' => $faculty->id
                ]);
                $attendanceRecords[] = $attendance;
            }
            
            // Collect student IDs for notifications
            $studentsToNotify[] = [
                'id' => $student->id,
                'name' => $student->user->name
            ];
        }
        
        // Send notifications to all students
        $fcmService = new \App\Services\FcmNotificationService();
        $formattedDate = \Carbon\Carbon::parse($validated['date'])->format('d M');
        
        foreach ($studentsToNotify as $student) {
            $attendanceStatus = collect($validated['attendances'])
                ->firstWhere('student_id', $student['id'])['status'];
                
            $fcmService->sendAttendanceNotification(
                $student['id'],
                $student['name'],
                $formattedDate,
                $attendanceStatus
            );
        }
        
        return response()->json([
            'message' => 'Attendance marked successfully',
            'attendance_count' => count($attendanceRecords),
            'date' => $validated['date'],
            'class' => $class->name . ' ' . $class->section
        ]);
    });
});

Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'API is reachable'
    ]);
});

Route::middleware(['auth.firebase', 'student.or.parent'])->group(function () {
    // Enhanced attendance endpoint for Android app
    Route::get('/student/attendance', function (Request $request) {
        $student = Student::where('user_id', auth()->id())
            ->where('school_id', school_id())
            ->first();
            
        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
                'message' => 'Student profile not found'
            ], 404);
        }
        
        $query = Attendance::with(['schoolClass'])
            ->where('student_id', $student->id)
            ->where('school_id', school_id());
            
        // Filter by month if provided
        if ($request->has('month')) {
            $month = $request->month; // Format: YYYY-MM
            $query->whereRaw("strftime('%Y-%m', date) = ?", [$month]);
        }
        
        $attendances = $query->orderBy('date', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date,
                    'status' => $attendance->status,
                    'class' => [
                        'name' => $attendance->schoolClass->name,
                        'section' => $attendance->schoolClass->section,
                    ],
                    'marked_by' => $attendance->markedBy->name ?? null,
                ];
            });
        // Calculate statistics
        $totalDays = $attendances->count();
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $percentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100, 2) : 0;
        
        return response()->json([
            'student' => [
                'id' => $student->id,
                'admission_no' => $student->admission_no,
                'name' => $student->user->name,
                'class' => $student->schoolClass->name . ' ' . $student->schoolClass->section,
            ],
            'attendances' => $attendances,
            'statistics' => [
                'total_days' => $totalDays,
                'present_days' => $presentDays,
                'absent_days' => $absentDays,
                'attendance_percentage' => $percentage,
            ],
            'available_months' => Attendance::where('student_id', $student->id)
                ->where('school_id', school_id())
                ->selectRaw("DISTINCT strftime('%Y-%m', date) as month")
                ->orderBy('month', 'desc')
                ->pluck('month')
        ]);
    });
    
    // Enhanced marks endpoint for Android app
    Route::get('/student/marks', function (Request $request) {
        $student = Student::where('user_id', auth()->id())
            ->where('school_id', school_id())
            ->first();
            
        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
                'message' => 'Student profile not found'
            ], 404);
        }
        
        // Get all marks for the student with exam and subject details
        $marks = Mark::with(['exam', 'examSubject', 'examSubject.subject'])
            ->where('student_id', $student->id)
            ->whereHas('exam', function ($query) {
                $query->where('school_id', school_id())->where('status', 'published');
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('exam_id');
        
        $examsData = [];
        $totalMarks = 0;
        $totalMaxMarks = 0;
        
        foreach ($marks as $examId => $examMarks) {
            $exam = $examMarks->first()->exam;
            $subjectsData = [];
            $examTotal = 0;
            $examMaxTotal = 0;
            
            foreach ($examMarks as $mark) {
                $subjectsData[] = [
                    'subject' => [
                        'name' => $mark->examSubject->subject->name,
                        'code' => $mark->examSubject->subject->code,
                    ],
                    'marks_obtained' => $mark->marks_obtained,
                    'max_marks' => $mark->examSubject->max_marks,
                    'percentage' => $mark->examSubject->max_marks > 0 
                        ? round(($mark->marks_obtained / $mark->examSubject->max_marks) * 100, 2) 
                        : 0,
                ];
                $examTotal += $mark->marks_obtained;
                $examMaxTotal += $mark->examSubject->max_marks;
            }
            
            $examsData[] = [
                'exam' => [
                    'id' => $exam->id,
                    'name' => $exam->name,
                    'exam_date' => $exam->exam_date,
                ],
                'subjects' => $subjectsData,
                'exam_statistics' => [
                    'total_marks_obtained' => $examTotal,
                    'total_max_marks' => $examMaxTotal,
                    'percentage' => $examMaxTotal > 0 ? round(($examTotal / $examMaxTotal) * 100, 2) : 0,
                ],
            ];
            
            $totalMarks += $examTotal;
            $totalMaxMarks += $examMaxTotal;
        }
        
        return response()->json([
            'student' => [
                'id' => $student->id,
                'admission_no' => $student->admission_no,
                'name' => $student->user->name,
                'class' => $student->schoolClass->name . ' ' . $student->schoolClass->section,
            ],
            'exams' => $examsData,
            'overall_statistics' => [
                'total_exams' => count($examsData),
                'total_marks_obtained' => $totalMarks,
                'total_max_marks' => $totalMaxMarks,
                'overall_percentage' => $totalMaxMarks > 0 ? round(($totalMarks / $totalMaxMarks) * 100, 2) : 0,
            ],
        ]);
    });
    
    // Profile endpoint for Android app
    Route::get('/student/profile', function () {
        $student = Student::with(['user', 'schoolClass', 'school'])
            ->where('user_id', auth()->id())
            ->where('school_id', school_id())
            ->first();
            
        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
                'message' => 'Student profile not found'
            ], 404);
        }
        
        return response()->json([
            'student' => [
                'id' => $student->id,
                'admission_no' => $student->admission_no,
                'name' => $student->user->name,
                'email' => $student->user->email,
                'class' => [
                    'id' => $student->schoolClass->id,
                    'name' => $student->schoolClass->name,
                    'section' => $student->schoolClass->section,
                ],
                'school' => [
                    'id' => $student->school->id,
                    'name' => $student->school->name,
                ],
                'status' => $student->status,
                'created_at' => $student->created_at->toISOString(),
            ]
        ]);
    });
    
    // FCM Token endpoint for Android app
    Route::post('/student/fcm-token', function (Request $request) {
        $student = Student::where('user_id', auth()->id())
            ->where('school_id', school_id())
            ->first();
            
        if (!$student) {
            return response()->json([
                'error' => 'Student not found',
                'message' => 'Student profile not found'
            ], 404);
        }
        
        $request->validate([
            'fcm_token' => 'required|string|max:255'
        ]);
        
        // Update or create FCM token record
        $student->fcm_token = $request->fcm_token;
        $student->save();
        
        return response()->json([
            'message' => 'FCM token updated successfully',
            'fcm_token' => $request->fcm_token
        ]);
    });
    
    // Device Token API
    Route::post('/device-token', function (Request $request) {
        $validated = $request->validate([
            'fcm_token' => 'required|string|max:255',
            'device_type' => 'sometimes|string|in:android,ios'
        ]);
        
        $user = auth()->user();
        $deviceType = $validated['device_type'] ?? 'android';
        
        // Find student record for this user
        $student = Student::where('user_id', $user->id)
            ->where('school_id', school_id())
            ->first();
        
        // Update or create device token
        $deviceToken = DeviceToken::updateOrCreateToken([
            'user_id' => $user->id,
            'student_id' => $student?->id,
            'school_id' => school_id(),
            'fcm_token' => $validated['fcm_token'],
            'device_type' => $deviceType
        ]);
        
        return response()->json([
            'message' => 'Device token saved successfully',
            'device_token' => [
                'id' => $deviceToken->id,
                'fcm_token' => $deviceToken->fcm_token,
                'device_type' => $deviceToken->device_type,
                'last_used_at' => $deviceToken->last_used_at
            ]
        ]);
    });
    
    // Remove device token (logout)
    Route::delete('/device-token', function (Request $request) {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
            'device_type' => 'sometimes|string|in:android,ios'
        ]);
        
        $deviceType = $validated['device_type'] ?? 'android';
        $deleted = DeviceToken::removeToken($validated['fcm_token'], $deviceType);
        
        return response()->json([
            'message' => $deleted ? 'Device token removed successfully' : 'Device token not found'
        ]);
    });
});

// API routes will be added here
// All routes will have school detection middleware applied

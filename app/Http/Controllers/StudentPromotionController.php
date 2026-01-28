<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\StudentClassHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentPromotionController extends Controller
{
    public function index()
    {
        $school = auth()->user()->school;
        
        $currentYear = date('Y');
        $nextYear = $currentYear + 1;
        $academicYear = "{$currentYear}-{$nextYear}";
        
        $classes = SchoolClass::where('school_id', $school->id)
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        return view('students.promotion.index', compact('classes', 'academicYear'));
    }

    public function getStudents(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
        ]);

        $school = auth()->user()->school;
        
        $students = Student::with(['user', 'schoolClass', 'currentClassHistory'])
            ->where('school_id', $school->id)
            ->where('class_id', $request->class_id)
            ->where('status', 'active')
            ->get();

        $availableClasses = SchoolClass::where('school_id', $school->id)
            ->where('id', '!=', $request->class_id)
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        return response()->json([
            'students' => $students,
            'availableClasses' => $availableClasses,
        ]);
    }

    public function promote(Request $request)
    {
        $request->validate([
            'promotions' => 'required|array|min:1',
            'promotions.*.student_id' => 'required|exists:students,id',
            'promotions.*.to_class_id' => 'required|exists:classes,id',
            'promotions.*.academic_year' => 'required|string',
            'promotions.*.from_date' => 'required|date',
            'promotions.*.remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $school = auth()->user()->school;
            $promotedCount = 0;

            foreach ($request->promotions as $promotion) {
                $student = Student::where('school_id', $school->id)
                    ->where('id', $promotion['student_id'])
                    ->first();

                if (!$student) {
                    continue;
                }

                // Complete current class history
                $currentHistory = $student->currentClassHistory;
                if ($currentHistory) {
                    $currentHistory->update([
                        'status' => 'completed',
                        'to_date' => $promotion['from_date'],
                    ]);
                }

                // Create new class history entry
                StudentClassHistory::create([
                    'student_id' => $student->id,
                    'class_id' => $promotion['to_class_id'],
                    'school_id' => $school->id,
                    'academic_year' => $promotion['academic_year'],
                    'from_date' => $promotion['from_date'],
                    'status' => 'active',
                    'remarks' => $promotion['remarks'] ?? null,
                ]);

                // Update student's current class
                $student->update([
                    'class_id' => $promotion['to_class_id'],
                ]);

                $promotedCount++;
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => "Successfully promoted {$promotedCount} students.",
                'promoted_count' => $promotedCount,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error promoting students: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function bulkPromote(Request $request)
    {
        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'to_class_id' => 'required|exists:classes,id',
            'academic_year' => 'required|string',
            'from_date' => 'required|date',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $school = auth()->user()->school;
            
            $students = Student::where('school_id', $school->id)
                ->where('class_id', $request->from_class_id)
                ->where('status', 'active')
                ->get();

            $promotedCount = 0;

            foreach ($students as $student) {
                // Complete current class history
                $currentHistory = $student->currentClassHistory;
                if ($currentHistory) {
                    $currentHistory->update([
                        'status' => 'completed',
                        'to_date' => $request->from_date,
                    ]);
                }

                // Create new class history entry
                StudentClassHistory::create([
                    'student_id' => $student->id,
                    'class_id' => $request->to_class_id,
                    'school_id' => $school->id,
                    'academic_year' => $request->academic_year,
                    'from_date' => $request->from_date,
                    'status' => 'active',
                    'remarks' => $request->remarks ?? null,
                ]);

                // Update student's current class
                $student->update([
                    'class_id' => $request->to_class_id,
                ]);

                $promotedCount++;
            }

            DB::commit();
            return back()->with('success', "Successfully promoted {$promotedCount} students.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error promoting students: ' . $e->getMessage());
        }
    }

    public function history(Request $request)
    {
        $school = auth()->user()->school;
        
        $query = StudentClassHistory::with(['student.user', 'class'])
            ->where('school_id', $school->id);

        if ($request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        if ($request->class_id) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->student_search) {
            $query->whereHas('student.user', function($q) use ($request) {
                $q->where('name', 'like', "%{$request->student_search}%");
            });
        }

        $histories = $query->orderBy('academic_year', 'desc')
            ->orderBy('from_date', 'desc')
            ->paginate(20);

        $academicYears = StudentClassHistory::where('school_id', $school->id)
            ->distinct()
            ->pluck('academic_year')
            ->sort()
            ->reverse();

        $classes = SchoolClass::where('school_id', $school->id)
            ->orderBy('name')
            ->orderBy('section')
            ->get();

        return view('students.promotion.history', compact('histories', 'academicYears', 'classes'));
    }
}

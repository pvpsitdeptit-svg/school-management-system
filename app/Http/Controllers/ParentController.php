<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\ParentStudent;
use App\Models\Student;
use App\Models\User;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ParentController extends Controller
{
    public function index(Request $request)
    {
        $school = auth()->user()->school;
        
        $parents = ParentModel::with(['user', 'studentDetails'])
            ->where('school_id', $school->id)
            ->when($request->search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('email', 'like', "%{$search}%");
                      });
            })
            ->orderBy('name')
            ->paginate(10);

        return view('parents.index', compact('parents'));
    }

    public function create()
    {
        $school = auth()->user()->school;
        $students = Student::with('user')
            ->where('school_id', $school->id)
            ->where('status', 'active')
            ->orderBy('admission_no')
            ->get();

        return view('parents.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'relationship' => 'required|in:father,mother,guardian',
            'password' => 'required|string|min:6',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'primary_contact_student' => 'nullable|exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            $school = auth()->user()->school;

            // Create user account
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'school_id' => $school->id,
                'role' => 'parent',
                'status' => 'active',
            ]);

            // Create parent record
            $parent = ParentModel::create([
                'school_id' => $school->id,
                'user_id' => $user->id,
                'name' => $request->name,
                'phone' => $request->phone,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'relationship' => $request->relationship,
            ]);

            // Link parent to students
            foreach ($request->students as $studentId) {
                ParentStudent::create([
                    'parent_id' => $parent->id,
                    'student_id' => $studentId,
                    'school_id' => $school->id,
                    'is_primary_contact' => $request->primary_contact_student == $studentId,
                ]);
            }

            DB::commit();
            return redirect()->route('parents.index')
                ->with('success', 'Parent created successfully and linked to students.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating parent: ' . $e->getMessage());
        }
    }

    public function show(ParentModel $parent)
    {
        $parent->load(['user', 'studentDetails.user', 'studentDetails.schoolClass']);
        
        return view('parents.show', compact('parent'));
    }

    public function edit(ParentModel $parent)
    {
        $school = auth()->user()->school;
        $students = Student::with('user')
            ->where('school_id', $school->id)
            ->where('status', 'active')
            ->orderBy('admission_no')
            ->get();

        $parent->load(['studentDetails']);
        $linkedStudentIds = $parent->studentDetails->pluck('id')->toArray();
        $primaryContactStudentId = $parent->studentDetails()
            ->wherePivot('is_primary_contact', true)
            ->first()?->id;

        return view('parents.edit', compact('parent', 'students', 'linkedStudentIds', 'primaryContactStudentId'));
    }

    public function update(Request $request, ParentModel $parent)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($parent->user_id)],
            'phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'relationship' => 'required|in:father,mother,guardian',
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'primary_contact_student' => 'nullable|exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            // Update user account
            $parent->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            // Update parent record
            $parent->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'relationship' => $request->relationship,
            ]);

            // Remove existing student links
            ParentStudent::where('parent_id', $parent->id)->delete();

            // Re-link parent to students
            foreach ($request->students as $studentId) {
                ParentStudent::create([
                    'parent_id' => $parent->id,
                    'student_id' => $studentId,
                    'school_id' => $parent->school_id,
                    'is_primary_contact' => $request->primary_contact_student == $studentId,
                ]);
            }

            DB::commit();
            return redirect()->route('parents.index')
                ->with('success', 'Parent updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating parent: ' . $e->getMessage());
        }
    }

    public function destroy(ParentModel $parent)
    {
        DB::beginTransaction();
        try {
            // Remove student links
            ParentStudent::where('parent_id', $parent->id)->delete();
            
            // Delete parent record
            $parent->delete();
            
            // Delete user account
            $parent->user->delete();

            DB::commit();
            return redirect()->route('parents.index')
                ->with('success', 'Parent deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error deleting parent: ' . $e->getMessage());
        }
    }

    public function linkStudents(Request $request, ParentModel $parent)
    {
        $request->validate([
            'students' => 'required|array|min:1',
            'students.*' => 'exists:students,id',
            'primary_contact_student' => 'nullable|exists:students,id',
        ]);

        DB::beginTransaction();
        try {
            // Remove existing links
            ParentStudent::where('parent_id', $parent->id)->delete();

            // Create new links
            foreach ($request->students as $studentId) {
                ParentStudent::create([
                    'parent_id' => $parent->id,
                    'student_id' => $studentId,
                    'school_id' => $parent->school_id,
                    'is_primary_contact' => $request->primary_contact_student == $studentId,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Students linked successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error linking students: ' . $e->getMessage());
        }
    }
}

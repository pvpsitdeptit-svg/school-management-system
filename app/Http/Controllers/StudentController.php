<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    /**
     * Display a listing of the students.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Start with base query
        $query = Student::with('user', 'schoolClass')
                        ->where('school_id', $user->school_id);
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admission_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('class')) {
            $query->where('class_id', $request->class);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('section')) {
            $query->whereHas('schoolClass', function($q) use ($request) {
                $q->where('section', $request->section);
            });
        }
        
        // Get students with pagination
        $students = $query->orderBy('created_at', 'desc')
                         ->paginate(15);
        
        // Get filter options
        $classes = SchoolClass::where('school_id', $user->school_id)
                             ->orderBy('name')
                             ->get();
        
        return view('students.index', compact('students', 'classes'));
    }
    
    /**
     * Show the form for creating a new student.
     */
    public function create()
    {
        $user = Auth::user();
        
        $classes = SchoolClass::where('school_id', $user->school_id)
                             ->orderBy('name')
                             ->get();
        
        return view('students.create', compact('classes'));
    }
    
    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'class_id' => 'required|exists:classes,id',
            'admission_no' => 'required|string|unique:students,admission_no',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
        ]);
        
        // Create user account
        $newUser = User::create([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
            'school_id' => $user->school_id,
        ]);
        
        // Create student record
        Student::create([
            'user_id' => $newUser->id,
            'school_id' => $user->school_id,
            'class_id' => $request->class_id,
            'admission_no' => $request->admission_no,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'status' => 'active',
        ]);
        
        return redirect()->route('students.index')
                         ->with('success', 'Student admitted successfully!');
    }
    
    /**
     * Display the specified student.
     */
    public function show(Student $student)
    {
        // Check if user has permission to view this student
        if (Auth::user()->school_id !== $student->school_id) {
            abort(403);
        }
        
        $student->load('user', 'schoolClass', 'attendances', 'marks');
        
        return view('students.show', compact('student'));
    }
    
    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student)
    {
        // Check if user has permission to edit this student
        if (Auth::user()->school_id !== $student->school_id) {
            abort(403);
        }
        
        $user = Auth::user();
        $classes = SchoolClass::where('school_id', $user->school_id)
                             ->orderBy('name')
                             ->get();
        
        return view('students.edit', compact('student', 'classes'));
    }
    
    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, Student $student)
    {
        // Check if user has permission to update this student
        if (Auth::user()->school_id !== $student->school_id) {
            abort(403);
        }
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($student->user_id),
            ],
            'class_id' => 'required|exists:classes,id',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive,graduated',
        ]);
        
        // Update user account
        $student->user->update([
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
        ]);
        
        // Update student record
        $student->update([
            'class_id' => $request->class_id,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,
            'status' => $request->status,
        ]);
        
        return redirect()->route('students.index')
                         ->with('success', 'Student updated successfully!');
    }
    
    /**
     * Remove the specified student from storage.
     */
    public function destroy(Student $student)
    {
        // Check if user has permission to delete this student
        if (Auth::user()->school_id !== $student->school_id) {
            abort(403);
        }
        
        // Delete user and student records
        $student->user->delete();
        $student->delete();
        
        return redirect()->route('students.index')
                         ->with('success', 'Student deleted successfully!');
    }
    
    /**
     * Show the bulk upload form.
     */
    public function bulkUpload()
    {
        $user = Auth::user();
        
        $classes = SchoolClass::where('school_id', $user->school_id)
                             ->orderBy('name')
                             ->get();
        
        return view('students.bulk-upload', compact('classes'));
    }
    
    /**
     * Process the bulk upload of students.
     */
    public function processBulkUpload(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240', // Max 10MB
            'class_id' => 'required|exists:classes,id',
        ]);
        
        try {
            $results = $this->processFileUpload($request->file('file'), $user->school_id, $request->class_id);
            
            return redirect()->route('students.index')
                             ->with('success', "Bulk upload completed! {$results['success']} students imported successfully.")
                             ->with('upload_results', $results);
                             
        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Error during bulk upload: ' . $e->getMessage())
                             ->withInput();
        }
    }
    
    /**
     * Process the uploaded file.
     */
    private function processFileUpload($file, $schoolId, $classId)
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];
        
        $filePath = $file->getPathname();
        $extension = $file->getClientOriginalExtension();
        
        if ($extension === 'csv') {
            $data = $this->parseCsv($filePath);
        } else {
            // For Excel files, we'll use a simple approach
            $data = $this->parseExcel($filePath);
        }
        
        $rowNumber = 2; // Start from row 2 (after header)
        
        foreach ($data as $row) {
            try {
                $this->processStudentRow($row, $schoolId, $classId);
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
            }
            
            $rowNumber++;
        }
        
        return $results;
    }
    
    /**
     * Parse CSV file.
     */
    private function parseCsv($filePath)
    {
        $data = [];
        $header = null;
        
        if (($handle = fopen($filePath, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        
        return $data;
    }
    
    /**
     * Parse Excel file (simplified version).
     */
    private function parseExcel($filePath)
    {
        // For now, let's convert Excel to CSV using a simple approach
        // In production, you might want to use a proper Excel library
        throw new \Exception('Excel files are not yet supported. Please use CSV format.');
    }
    
    /**
     * Process a single student row.
     */
    private function processStudentRow($row, $schoolId, $classId)
    {
        // Clean up the data
        $firstName = trim($row['first_name'] ?? '');
        $lastName = trim($row['last_name'] ?? '');
        $email = trim($row['email'] ?? '');
        
        // Validate required fields
        if (empty($firstName) || empty($lastName) || empty($email)) {
            throw new \Exception("First name, last name, and email are required");
        }
        
        // Check if email already exists
        if (User::where('email', $email)->exists()) {
            throw new \Exception("Email '{$email}' already exists");
        }
        
        // Generate admission number if not provided
        $admissionNo = !empty($row['admission_no']) ? trim($row['admission_no']) : $this->generateAdmissionNumber();
        
        // Check if admission number already exists
        if (Student::where('admission_no', $admissionNo)->exists()) {
            throw new \Exception("Admission number '{$admissionNo}' already exists");
        }
        
        // Create user account
        $user = User::create([
            'name' => trim($firstName . ' ' . $lastName),
            'email' => $email,
            'password' => Hash::make(!empty($row['password']) ? $row['password'] : 'password123'), // Default password
            'role' => 'student',
            'school_id' => $schoolId,
        ]);
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'school_id' => $schoolId,
            'class_id' => $classId,
            'admission_no' => $admissionNo,
            'phone' => $row['phone'] ?? null,
            'date_of_birth' => !empty($row['date_of_birth']) ? date('Y-m-d', strtotime($row['date_of_birth'])) : null,
            'gender' => $row['gender'] ?? null,
            'address' => $row['address'] ?? null,
            'status' => !empty($row['status']) ? $row['status'] : 'active',
        ]);
    }
    
    /**
     * Generate admission number.
     */
    private function generateAdmissionNumber()
    {
        $prefix = 'STU';
        $year = date('Y');
        $sequence = Student::whereYear('created_at', $year)->count() + 1;
        
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Download the CSV template for bulk student upload.
     */
    public function downloadTemplate()
    {
        $templateData = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@school.com',
                'phone' => '+1234567890',
                'date_of_birth' => '2005-05-15',
                'gender' => 'male',
                'address' => '123 Main Street, City',
                'password' => 'student123',
                'status' => 'active',
                'admission_no' => 'STU20240001'
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@school.com',
                'phone' => '+1234567891',
                'date_of_birth' => '2005-08-22',
                'gender' => 'female',
                'address' => '456 Oak Avenue, City',
                'password' => 'student123',
                'status' => 'active',
                'admission_no' => 'STU20240002'
            ]
        ];
        
        $csvContent = $this->arrayToCsv($templateData);
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="students_template.csv"');
    }
    
    /**
     * Export students to Excel.
     */
    public function exportStudents(Request $request)
    {
        $user = Auth::user();
        
        // Get filtered students (same logic as index method)
        $query = Student::with('user', 'schoolClass')
                        ->where('school_id', $user->school_id);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('admission_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($subQuery) use ($search) {
                      $subQuery->where('name', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        if ($request->filled('class')) {
            $query->where('class_id', $request->class);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $students = $query->get();
        
        // Create a simple array for export
        $exportData = [];
        foreach ($students as $student) {
            $exportData[] = [
                'Admission No' => $student->admission_no,
                'First Name' => $student->user->name ? explode(' ', $student->user->name)[0] : '',
                'Last Name' => $student->user->name ? implode(' ', array_slice(explode(' ', $student->user->name), 1)) : '',
                'Email' => $student->user->email,
                'Phone' => $student->phone,
                'Class' => $student->schoolClass->name ?? '',
                'Section' => $student->schoolClass->section ?? '',
                'Date of Birth' => $student->date_of_birth,
                'Gender' => $student->gender,
                'Address' => $student->address,
                'Status' => $student->status,
                'Admission Date' => $student->created_at->format('Y-m-d'),
            ];
        }
        
        // Create CSV content
        $csvContent = $this->arrayToCsv($exportData);
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="students_export.csv"');
    }
    
    /**
     * Convert array to CSV format.
     */
    private function arrayToCsv($data)
    {
        if (empty($data)) {
            return '';
        }
        
        $output = fopen('php://temp', 'r+');
        
        // Add header
        fputcsv($output, array_keys($data[0]));
        
        // Add data rows
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return $csv;
    }
}

<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements ToCollection, WithHeadingRow, WithValidation
{
    protected $schoolId;
    protected $classId;
    protected $results = [
        'success' => 0,
        'failed' => 0,
        'errors' => []
    ];
    
    public function __construct($schoolId, $classId)
    {
        $this->schoolId = $schoolId;
        $this->classId = $classId;
    }
    
    public function collection(Collection $rows)
    {
        $rowNumber = 2; // Start from row 2 (after header)
        
        foreach ($rows as $row) {
            try {
                $this->processStudentRow($row, $rowNumber);
                $this->results['success']++;
            } catch (\Exception $e) {
                $this->results['failed']++;
                $this->results['errors'][] = "Row {$rowNumber}: " . $e->getMessage();
            }
            
            $rowNumber++;
        }
    }
    
    private function processStudentRow($row, $rowNumber)
    {
        // Validate required fields
        if (empty($row['first_name']) || empty($row['last_name']) || empty($row['email'])) {
            throw new \Exception("First name, last name, and email are required");
        }
        
        // Check if email already exists
        if (User::where('email', $row['email'])->exists()) {
            throw new \Exception("Email '{$row['email']}' already exists");
        }
        
        // Generate admission number if not provided
        $admissionNo = !empty($row['admission_no']) ? $row['admission_no'] : $this->generateAdmissionNumber();
        
        // Check if admission number already exists
        if (Student::where('admission_no', $admissionNo)->exists()) {
            throw new \Exception("Admission number '{$admissionNo}' already exists");
        }
        
        // Create user account
        $user = User::create([
            'name' => trim($row['first_name'] . ' ' . $row['last_name']),
            'email' => $row['email'],
            'password' => Hash::make(!empty($row['password']) ? $row['password'] : 'password123'), // Default password
            'role' => 'student',
            'school_id' => $this->schoolId,
        ]);
        
        // Create student record
        Student::create([
            'user_id' => $user->id,
            'school_id' => $this->schoolId,
            'class_id' => $this->classId,
            'admission_no' => $admissionNo,
            'phone' => $row['phone'] ?? null,
            'date_of_birth' => !empty($row['date_of_birth']) ? date('Y-m-d', strtotime($row['date_of_birth'])) : null,
            'gender' => $row['gender'] ?? null,
            'address' => $row['address'] ?? null,
            'status' => !empty($row['status']) ? $row['status'] : 'active',
        ]);
    }
    
    private function generateAdmissionNumber()
    {
        $prefix = 'STU';
        $year = date('Y');
        $sequence = Student::whereYear('created_at', $year)->count() + 1;
        
        return $prefix . $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
    
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6',
            'status' => 'nullable|in:active,inactive,graduated',
        ];
    }
    
    public function getResults()
    {
        return $this->results;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use App\Models\Student;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SuperAdminController extends Controller
{
    protected $firebaseService;
    
    public function __construct(FirebaseAuthService $firebaseService = null)
    {
        $this->firebaseService = $firebaseService ?: app(FirebaseAuthService::class);
    }
    
    /**
     * Create user in Firebase Authentication.
     */
    private function createFirebaseUser($email, $password, $name)
    {
        $result = $this->firebaseService->createUser($email, $password, $name);
        return $result;
    }
    
    /**
     * Display the Super Admin dashboard.
     */
    public function dashboard(Request $request)
    {
        // Get real statistics from database
        $totalSchools = School::count();
        $activeSchools = School::where('status', 'active')->count();
        $suspendedSchools = School::where('status', 'suspended')->count();
        
        // Get total students across all schools
        $totalStudents = Student::count();
        
        // Calculate percentages
        $activePercentage = $totalSchools > 0 ? round(($activeSchools / $totalSchools) * 100, 1) : 0;
        
        // Get recent schools with student counts
        $recentSchools = School::withCount('students')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Calculate changes (mock data for now, can be enhanced with real analytics)
        $schoolsChange = 2; // Can be calculated from last month
        $studentsChange = 156; // Can be calculated from last week
        $suspendedChange = -1; // Can be calculated from last month
        
        $stats = [
            'total_schools' => $totalSchools,
            'active_schools' => $activeSchools,
            'suspended_schools' => $suspendedSchools,
            'total_students' => $totalStudents,
            'active_percentage' => $activePercentage,
            'schools_change' => $schoolsChange,
            'students_change' => $studentsChange,
            'suspended_change' => $suspendedChange,
            'recent_schools' => $recentSchools,
        ];
        
        return view('super-admin.dashboard', compact('stats'));
    }
    
    /**
     * Display list of all schools.
     */
    public function schools(Request $request)
    {
        $query = School::withCount('students');
        
        // Apply filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('subdomain', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $schools = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('super-admin.schools', compact('schools'));
    }
    
    /**
     * Show the form for creating a new school.
     */
    public function createSchool()
    {
        return view('super-admin.create-school');
    }
    
    /**
     * Store a newly created school in storage.
     */
    public function storeSchool(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:schools,subdomain',
            'email' => 'required|email|unique:schools,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:6',
        ]);
        
        // Create school
        $school = School::create([
            'name' => $request->name,
            'code' => strtoupper(substr($request->domain, 0, 3) . rand(100, 999)),
            'subdomain' => $request->domain,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => 'active',
        ]);
        
        // Create user in Firebase Authentication
        $firebaseResult = $this->firebaseService->createUser(
            $request->admin_email,
            $request->admin_password,
            $request->admin_name
        );
        
        $firebaseUid = null;
        if ($firebaseResult['success']) {
            $firebaseUid = $firebaseResult['data']['localId'] ?? null;
            Log::info("Firebase user created successfully: {$request->admin_email}, UID: {$firebaseUid}");
        } else {
            Log::error("Firebase user creation failed for {$request->admin_email}: " . $firebaseResult['message']);
            // Don't fail the operation, but log the error
        }
        
        // Create school admin user with Firebase UID
        try {
            Log::info("Attempting to create user in database:");
            Log::info("Name: {$request->admin_name}");
            Log::info("Email: {$request->admin_email}");
            Log::info("Role: school_admin");
            Log::info("School ID: {$school->id}");
            Log::info("Firebase UID: " . ($firebaseUid ?? 'NULL'));
            
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'password' => null, // Don't store password for Firebase users
                'role' => 'school_admin',
                'school_id' => $school->id,
                'firebase_uid' => $firebaseUid,
                'status' => 'active', // Add status field
            ]);
            
            Log::info("User created successfully in database with ID: {$admin->id}");
            
        } catch (\Exception $e) {
            Log::error("Failed to create user in database: " . $e->getMessage());
            Log::error("Stack trace: " . $e->getTraceAsString());
            
            // Still redirect but with error info
            return redirect()->route('super-admin.schools')
                             ->with('success', 'School created but admin user creation failed: ' . $e->getMessage());
        }
        
        return redirect()->route('super-admin.schools')
                         ->with('success', 'School created successfully!');
    }
    
    /**
     * Show the form for editing a school.
     */
    public function editSchool(School $school)
    {
        $school->load('admin');
        return view('super-admin.edit-school', compact('school'));
    }
    
    /**
     * Update the specified school in storage.
     */
    public function updateSchool(Request $request, School $school)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:schools,subdomain,' . $school->id,
            'email' => 'required|email|unique:schools,email,' . $school->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => 'required|in:active,suspended',
        ]);
        
        $school->update([
            'name' => $request->name,
            'subdomain' => $request->domain,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
        ]);
        
        return redirect()->route('super-admin.schools')
                         ->with('success', 'School updated successfully!');
    }
    
    /**
     * Remove the specified school from storage.
     */
    public function destroySchool(School $school)
    {
        // In real implementation, you might want to soft delete
        // and handle data cleanup properly
        $school->delete();
        
        return redirect()->route('super-admin.schools')
                         ->with('success', 'School deleted successfully!');
    }
    
    /**
     * View school statistics.
     */
    public function schoolStats(School $school)
    {
        $stats = [
            'total_students' => $school->students()->count(),
            'total_faculty' => $school->users()->where('role', 'faculty')->count(),
            'total_classes' => $school->classes()->count(),
            'total_subjects' => $school->subjects()->count(),
            'recent_activity' => [], // Would come from activity logs
        ];
        
        return view('super-admin.school-stats', compact('school', 'stats'));
    }
    
    /**
     * Show export reports page.
     */
    public function export()
    {
        return view('super-admin.export');
    }
    
    /**
     * Show platform settings page.
     */
    public function settings()
    {
        return view('super-admin.settings');
    }
    
    /**
     * Export schools data.
     */
    public function exportSchools()
    {
        $schools = School::withCount('students')->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="schools.csv"',
        ];
        
        $callback = function() use ($schools) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID', 'Name', 'Code', 'Subdomain', 'Email', 'Status', 'Students Count', 'Created At']);
            
            // CSV Data
            foreach ($schools as $school) {
                fputcsv($file, [
                    $school->id,
                    $school->name,
                    $school->code,
                    $school->subdomain,
                    $school->email,
                    $school->status,
                    $school->students_count,
                    $school->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Export students data.
     */
    public function exportStudents()
    {
        $students = Student::with(['school', 'user'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students.csv"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            
            // CSV Header
            fputcsv($file, ['ID', 'Name', 'Email', 'School', 'Class', 'Status', 'Created At']);
            
            // CSV Data
            foreach ($students as $student) {
                fputcsv($file, [
                    $student->id,
                    $student->name,
                    $student->user->email ?? 'N/A',
                    $student->school->name ?? 'N/A',
                    $student->class ?? 'N/A',
                    $student->status ?? 'active',
                    $student->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

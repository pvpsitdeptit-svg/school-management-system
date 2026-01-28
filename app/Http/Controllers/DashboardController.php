<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;

class DashboardController extends Controller
{
    /**
     * Display the school admin dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $schoolId = $user->school_id;
        
        // Get school-specific statistics
        $stats = [
            'total_students' => Student::where('school_id', $schoolId)->count(),
            'total_faculty' => User::where('school_id', $schoolId)->where('role', 'faculty')->count(),
            'total_classes' => SchoolClass::where('school_id', $schoolId)->count(),
            'total_subjects' => Subject::where('school_id', $schoolId)->count(),
        ];
        
        return view('dashboard', compact('stats'));
    }
    
    /**
     * Get dashboard statistics based on user role.
     */
    private function getDashboardStats($user)
    {
        $stats = [
            'total_students' => 0,
            'total_faculty' => 0,
            'total_classes' => 0,
            'total_subjects' => 0,
            'recent_activities' => []
        ];
        
        if ($user->role === 'admin') {
            // Admin can see all statistics
            $stats['total_students'] = \App\Models\Student::where('school_id', $user->school_id)->count();
            $stats['total_faculty'] = \App\Models\User::where('school_id', $user->school_id)
                                                   ->where('role', 'faculty')
                                                   ->count();
            $stats['total_classes'] = \App\Models\SchoolClass::where('school_id', $user->school_id)->count();
            $stats['total_subjects'] = \App\Models\Subject::count();
            
            // Get recent activities (placeholder for now)
            $stats['recent_activities'] = [
                [
                    'icon' => 'fa-user-plus',
                    'text' => 'New student admitted',
                    'time' => '2 hours ago',
                    'color' => 'success'
                ],
                [
                    'icon' => 'fa-calendar-check',
                    'text' => 'Attendance marked for Class 10-A',
                    'time' => '3 hours ago',
                    'color' => 'primary'
                ],
                [
                    'icon' => 'fa-file-alt',
                    'text' => 'Exam results published',
                    'time' => '5 hours ago',
                    'color' => 'warning'
                ]
            ];
        } elseif ($user->role === 'faculty') {
            // Faculty can see limited statistics
            $stats['total_students'] = \App\Models\Student::where('school_id', $user->school_id)->count();
            $stats['total_classes'] = \App\Models\SchoolClass::where('school_id', $user->school_id)->count();
            
            // Get faculty-specific activities
            $stats['recent_activities'] = [
                [
                    'icon' => 'fa-calendar-check',
                    'text' => 'Attendance marked today',
                    'time' => '1 hour ago',
                    'color' => 'success'
                ],
                [
                    'icon' => 'fa-chart-line',
                    'text' => 'Marks updated for Mathematics',
                    'time' => '4 hours ago',
                    'color' => 'primary'
                ]
            ];
        } elseif ($user->role === 'student') {
            // Student can see their own statistics
            $student = \App\Models\Student::where('user_id', $user->id)->first();
            if ($student) {
                $stats['total_students'] = 1; // Just themselves
                $stats['recent_activities'] = [
                    [
                        'icon' => 'fa-calendar-check',
                        'text' => 'Attendance recorded',
                        'time' => 'Today',
                        'color' => 'success'
                    ],
                    [
                        'icon' => 'fa-file-alt',
                        'text' => 'Exam results available',
                        'time' => '2 days ago',
                        'color' => 'primary'
                    ]
                ];
            }
        }
        
        return $stats;
    }
}

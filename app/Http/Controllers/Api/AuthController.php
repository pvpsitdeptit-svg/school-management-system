<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\FirebaseAuthService;

class AuthController extends Controller
{
    protected $firebaseService;
    
    public function __construct(FirebaseAuthService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    
    /**
     * API Login - Accepts Authorization: Bearer <firebase_id_token>
     * Follows the exact flow you described:
     * 1. Client sends token to Laravel
     * 2. Laravel verifies token
     * 3. Laravel finds user in DB
     * 4. Laravel identifies role
     */
    public function login(Request $request)
    {
        // Step 2: Client sends token to Laravel
        $token = $request->bearerToken();
        
        if (!$token) {
            return response()->json([
                'error' => 'Token required',
                'message' => 'Authorization: Bearer <firebase_id_token> header required'
            ], 401);
        }
        
        try {
            // Step 3: Laravel verifies token
            $firebaseUser = $this->verifyFirebaseToken($token);
            
            if (!$firebaseUser) {
                return response()->json([
                    'error' => 'Invalid token',
                    'message' => 'Firebase ID token is invalid or expired'
                ], 401);
            }
            
            $uid = $firebaseUser['localId'];
            
            // Step 4: Laravel finds user in DB
            $user = User::where('firebase_uid', $uid)->first();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not found',
                    'message' => 'User with this Firebase UID not found in system'
                ], 403);
            }
            
            // Step 5: Laravel identifies role and creates session
            Auth::login($user);
            
            Log::info("API User authenticated: {$user->email} (UID: {$uid}, Role: {$user->role})");
            
            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'school_id' => $user->school_id,
                    'firebase_uid' => $user->firebase_uid,
                ],
                'permissions' => $this->getUserPermissions($user->role),
                'message' => 'Authentication successful'
            ]);
            
        } catch (\Exception $e) {
            Log::error("API Authentication error: " . $e->getMessage());
            return response()->json([
                'error' => 'Authentication failed',
                'message' => 'An error occurred during authentication'
            ], 401);
        }
    }
    
    /**
     * Get user permissions based on role
     * Laravel decides what the user can do
     */
    private function getUserPermissions($role)
    {
        switch ($role) {
            case 'super_admin':
                return [
                    'can_manage_schools' => true,
                    'can_create_schools' => true,
                    'can_delete_schools' => true,
                    'can_export_reports' => true,
                    'can_manage_platform_settings' => true,
                ];
                
            case 'school_admin':
                return [
                    'can_manage_students' => true,
                    'can_manage_faculty' => true,
                    'can_manage_classes' => true,
                    'can_manage_subjects' => true,
                    'can_view_reports' => true,
                ];
                
            case 'faculty':
                return [
                    'can_manage_attendance' => true,
                    'can_manage_marks' => true,
                    'can_view_students' => true,
                    'can_manage_assignments' => true,
                ];
                
            case 'student':
                return [
                    'can_view_profile' => true,
                    'can_view_marks' => true,
                    'can_view_attendance' => true,
                    'can_view_assignments' => true,
                ];
                
            default:
                return [];
        }
    }
    
    /**
     * Verify Firebase ID Token
     */
    private function verifyFirebaseToken($idToken)
    {
        try {
            $apiKey = env('FIREBASE_API_KEY');
            if (!$apiKey) {
                Log::error('Firebase API key not configured');
                return null;
            }
            
            $response = \Illuminate\Support\Facades\Http::post(
                "https://identitytoolkit.googleapis.com/v1/accounts:lookup?key={$apiKey}",
                [
                    'idToken' => $idToken
                ]
            );
            
            $data = $response->json();
            
            if ($response->successful() && isset($data['users'][0])) {
                return $data['users'][0];
            }
            
            Log::error("Firebase token verification failed: " . $response->body());
            return null;
            
        } catch (\Exception $e) {
            Log::error("Firebase token verification error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get current authenticated user info
     */
    public function me(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'school_id' => $user->school_id,
                'firebase_uid' => $user->firebase_uid,
            ],
            'permissions' => $this->getUserPermissions($user->role),
        ]);
    }
    
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}

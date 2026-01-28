<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\FirebaseAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $firebaseService;
    
    public function __construct(FirebaseAuthService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application using Firebase ID Token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $request->validate([
            'id_token' => 'required|string',
        ]);

        try {
            // Step 3: Laravel verifies token
            $firebaseUser = $this->verifyFirebaseToken($request->id_token);
            
            if (!$firebaseUser) {
                return response()->json(['error' => 'Invalid token'], 401);
            }
            
            $uid = $firebaseUser['localId'];
            
            // Step 4: Laravel finds user in DB
            $user = \App\Models\User::where('firebase_uid', $uid)->first();
            
            if (!$user) {
                return response()->json(['error' => 'User not found'], 403);
            }
            
            // Step 5: Laravel identifies role and logs in
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            
            Log::info("User authenticated: {$user->email} (UID: {$uid}, Role: {$user->role})");
            
            // Redirect based on role
            return $this->redirectBasedOnRole($user);
            
        } catch (\Exception $e) {
            Log::error("Authentication error: " . $e->getMessage());
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }
    
    /**
     * Verify Firebase ID Token.
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
     * Redirect user based on their role.
     * Step 5: Laravel identifies role and decides what user can do.
     */
    private function redirectBasedOnRole($user)
    {
        // Laravel decides what the user can do based on role
        if ($user->role === 'super_admin') {
            // Super Admin - can manage all schools
            return redirect()->intended('/super-admin/dashboard');
        }
        
        if ($user->role === 'school_admin') {
            // School Admin - can manage their school
            return redirect()->intended('/dashboard');
        }
        
        if ($user->role === 'faculty') {
            // Faculty - can manage classes and students
            return redirect()->intended('/dashboard');
        }
        
        if ($user->role === 'student') {
            // Student - can view their own data
            return redirect()->intended('/dashboard');
        }
        
        // Default fallback
        return redirect()->intended('/dashboard');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flush();

        return redirect('/login')->with('status', 'You have been logged out successfully.');
    }
}

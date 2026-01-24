<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\FirebaseService;
use App\Models\User;
use Exception;
use Illuminate\Support\Str;

class AuthenticateFirebase
{
    private $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug logging
        \Log::info('Auth Middleware Debug:', [
            'authorization_header' => $request->header('Authorization'),
            'bearer_token' => $request->bearerToken(),
            'request_path' => $request->path(),
            'request_method' => $request->method(),
        ]);

        $token = $this->extractTokenFromRequest($request);

        if (!$token) {
            \Log::error('Token missing from request');
            return response()->json([
                'error' => 'Authorization token required',
                'message' => 'Please provide a Firebase ID token in the Authorization header',
                'debug' => 'Token extraction failed'
            ], 401);
        }

        try {
            // Debug: Log token verification attempt
            \Log::info('Attempting to verify Firebase token', ['token_length' => strlen($token)]);
            
            // Verify Firebase token
            $firebaseUid = $this->firebaseService->getFirebaseUid($token);
            
            \Log::info('Firebase verification result', ['firebase_uid' => $firebaseUid]);
            
            if (!$firebaseUid) {
                \Log::error('Firebase token verification failed');
                return response()->json([
                    'error' => 'Invalid token',
                    'message' => 'The provided Firebase token is invalid or expired',
                    'debug' => 'Token verification failed'
                ], 401);
            }

            // Find or create user
            $user = $this->findOrCreateUser($firebaseUid, $token);
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not found',
                    'message' => 'Unable to authenticate user'
                ], 401);
            }

            // Set authenticated user
            auth()->setUser($user);
            
            // Add user to request for easy access
            $request->merge(['authenticated_user' => $user]);

            return $next($request);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Authentication failed',
                'message' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Extract token from Authorization header
     */
    private function extractTokenFromRequest(Request $request): ?string
    {
        $authorization = $request->header('Authorization');
        
        if (!$authorization) {
            return null;
        }

        // Remove "Bearer " prefix if present
        if (str_starts_with($authorization, 'Bearer ')) {
            return substr($authorization, 7);
        }

        return $authorization;
    }

    /**
     * Find existing user or create new one from Firebase token
     */
    private function findOrCreateUser(string $firebaseUid, string $token): ?User
    {
        // First try to find existing user by Firebase UID
        $user = User::where('firebase_uid', $firebaseUid)->first();

        if ($user) {
            return $user;
        }

        // If user doesn't exist, get user info from Firebase token
        $email = $this->firebaseService->getUserEmail($token);
        $name = $this->firebaseService->getUserName($token);

        if (!$email) {
            throw new Exception('Email is required from Firebase token');
        }

        // For new users, we need to determine which school they belong to
        // This could be done through:
        // 1. Registration process where they select school
        // 2. Invitation system
        // 3. Admin approval
        
        // For now, we'll create a pending user that needs to be assigned to a school
        $user = User::create([
            'name' => $name ?? 'Unknown User',
            'email' => $email,
            'password' => bcrypt(Str::random(32)), // Random password, won't be used
            'firebase_uid' => $firebaseUid,
            'role' => 'student', // Default role, can be changed by admin
            'status' => 'pending', // Pending school assignment
            'school_id' => null, // Will be set when school is assigned
        ]);

        return $user;
    }
}

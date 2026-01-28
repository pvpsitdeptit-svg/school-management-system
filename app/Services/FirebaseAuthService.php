<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FirebaseAuthService
{
    private $apiKey;
    private $projectId;

    public function __construct()
    {
        $this->apiKey = env('FIREBASE_API_KEY');
        $this->projectId = env('FIREBASE_PROJECT_ID');
    }

    /**
     * Create a new user in Firebase Authentication
     */
    public function createUser($email, $password, $displayName = null)
    {
        try {
            if (!$this->apiKey) {
                Log::warning('Firebase API key not configured');
                return [
                    'success' => false,
                    'message' => 'Firebase API key not configured'
                ];
            }

            // Use the correct endpoint for user creation (signup)
            $response = Http::post("https://identitytoolkit.googleapis.com/v1/accounts:signUp?key={$this->apiKey}", [
                'email' => $email,
                'password' => $password,
                'displayName' => $displayName,
                'returnSecureToken' => false
            ]);

            $data = $response->json();

            if ($response->successful()) {
                Log::info("Firebase user created successfully: {$email}, UID: {$data['localId']}");
                return [
                    'success' => true,
                    'data' => $data,
                    'message' => 'User created successfully in Firebase'
                ];
            } else {
                Log::error("Firebase user creation failed: " . $response->body());
                return [
                    'success' => false,
                    'message' => $data['error']['message'] ?? 'Firebase user creation failed',
                    'error' => $data['error'] ?? null
                ];
            }
        } catch (\Exception $e) {
            Log::error("Firebase user creation error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Exception occurred: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if Firebase is configured
     */
    public function isConfigured()
    {
        return !empty($this->apiKey);
    }
}

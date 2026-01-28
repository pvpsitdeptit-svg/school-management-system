<?php

namespace App\Services;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Http;
use Exception;

class FirebaseService
{
    private $projectId;
    private $publicKeyUrl;

    public function __construct()
    {
        $this->projectId = config('services.fcm.project_id');
        $this->publicKeyUrl = "https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com";
        
        // Debug: Log Firebase configuration
        \Log::info('Firebase Service initialized', [
            'project_id' => $this->projectId,
            'project_id_set' => !empty($this->projectId)
        ]);
    }

    /**
     * Verify Firebase ID token and return decoded payload
     */
    public function verifyToken(string $idToken): ?array
    {
        try {
            \Log::info('Starting Firebase token verification');
            
            // Get public keys from Google
            $publicKeys = $this->getPublicKeys();
            \Log::info('Public keys fetched', ['count' => count($publicKeys)]);
            
            // Decode token header to get key ID
            $tokenParts = explode('.', $idToken);
            if (count($tokenParts) !== 3) {
                throw new Exception('Invalid token format');
            }
            
            $header = json_decode(base64_decode(strtr($tokenParts[0], '-_', '+/')));
            $keyId = $header->kid ?? null;
            
            \Log::info('Token header decoded', ['key_id' => $keyId]);
            
            if (!$keyId || !isset($publicKeys[$keyId])) {
                throw new Exception('Invalid token key ID: ' . ($keyId ?? 'null'));
            }

            // Verify the token with the correct public key
            $payload = JWT::decode($idToken, new Key($publicKeys[$keyId], 'RS256'));
            \Log::info('Token verified successfully', ['uid' => $payload->sub ?? null]);

            // Validate token claims
            $this->validateTokenClaims($payload);

            return (array) $payload;

        } catch (Exception $e) {
            \Log::error('Firebase token verification failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get public keys from Google
     */
    private function getPublicKeys(): array
    {
        \Log::info('Fetching public keys from Google', ['url' => $this->publicKeyUrl]);
        
        $response = Http::get($this->publicKeyUrl);
        
        if (!$response->successful()) {
            \Log::error('Failed to fetch public keys', ['status' => $response->status()]);
            throw new Exception('Failed to fetch public keys');
        }

        $keys = $response->json();
        \Log::info('Public keys fetched successfully', ['count' => count($keys)]);
        
        return $keys;
    }

    /**
     * Validate token claims
     */
    private function validateTokenClaims($payload): void
    {
        // Check if token is expired
        if (isset($payload->exp) && $payload->exp < time()) {
            throw new Exception('Token has expired');
        }

        // Check if token is issued in the future
        if (isset($payload->iat) && $payload->iat > time()) {
            throw new Exception('Token issued in the future');
        }

        // Check if token is for the correct project
        if (isset($payload->aud) && $payload->aud !== $this->projectId) {
            throw new Exception('Token audience mismatch');
        }

        // Check if token is issued by Firebase
        if (isset($payload->iss) && !str_contains($payload->iss, 'https://securetoken.google.com/')) {
            throw new Exception('Invalid token issuer');
        }
    }

    /**
     * Get Firebase UID from verified token
     */
    public function getFirebaseUid(string $idToken): ?string
    {
        $payload = $this->verifyToken($idToken);
        return $payload['sub'] ?? null;
    }

    /**
     * Get user email from verified token
     */
    public function getUserEmail(string $idToken): ?string
    {
        $payload = $this->verifyToken($idToken);
        return $payload['email'] ?? null;
    }

    /**
     * Get user name from verified token
     */
    public function getUserName(string $idToken): ?string
    {
        $payload = $this->verifyToken($idToken);
        return $payload['name'] ?? null;
    }
}

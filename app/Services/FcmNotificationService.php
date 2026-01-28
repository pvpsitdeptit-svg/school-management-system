<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Student;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FcmNotificationService
{
    private $projectId;
    private $privateKey;
    private $clientEmail;
    private $serverKey;
    private $useServiceAccount;

    public function __construct()
    {
        // Try Service Account first (modern approach)
        $this->projectId = config('services.fcm.project_id');
        $this->privateKey = config('services.fcm.private_key');
        $this->clientEmail = config('services.fcm.client_email');
        
        // Fallback to legacy server key
        $this->serverKey = config('services.fcm.server_key');
        
        // Determine which method to use
        $this->useServiceAccount = !empty($this->projectId) && !empty($this->privateKey) && !empty($this->clientEmail);
        
        if ($this->useServiceAccount) {
            Log::info('Using FCM Service Account authentication');
        } else {
            Log::info('Using FCM Server Key authentication (legacy)');
        }
    }

    /**
     * Send notification to specific student
     */
    public function sendToStudent(int $studentId, string $title, string $body, array $data = [])
    {
        $tokens = DeviceToken::getStudentTokens($studentId);
        
        if (empty($tokens)) {
            Log::info("No FCM tokens found for student ID: {$studentId}");
            return false;
        }

        return $this->sendNotification($tokens, $title, $body, $data);
    }

    /**
     * Send notification to multiple students
     */
    public function sendToStudents(array $studentIds, string $title, string $body, array $data = [])
    {
        $allTokens = [];
        
        foreach ($studentIds as $studentId) {
            $tokens = DeviceToken::getStudentTokens($studentId);
            $allTokens = array_merge($allTokens, $tokens);
        }

        if (empty($allTokens)) {
            Log::info("No FCM tokens found for students: " . implode(', ', $studentIds));
            return false;
        }

        // Remove duplicates
        $uniqueTokens = array_unique($allTokens);
        
        return $this->sendNotification($uniqueTokens, $title, $body, $data);
    }

    /**
     * Send attendance notification to student
     */
    public function sendAttendanceNotification(int $studentId, string $studentName, string $date, string $status)
    {
        $title = "Attendance Updated";
        $body = "Attendance for {$studentName} – {$date}: " . ucfirst($status);
        
        $data = [
            'type' => 'attendance',
            'date' => $date,
            'status' => $status
        ];

        return $this->sendToStudent($studentId, $title, $body, $data);
    }

    /**
     * Send exam results notification to class students
     */
    public function sendExamResultsNotification(array $studentIds, string $examName)
    {
        $title = "Exam Results Published";
        $body = "{$examName} results are now available.";
        
        $data = [
            'type' => 'marks',
            'exam_name' => $examName
        ];

        return $this->sendToStudents($studentIds, $title, $body, $data);
    }

    /**
     * Send notification via FCM
     */
    private function sendNotification(array $tokens, string $title, string $body, array $data = [])
    {
        $notification = [
            'title' => $title,
            'body' => $body,
            'sound' => 'default',
            'badge' => 1
        ];

        $payload = [
            'message' => [
                'token' => $tokens[0], // Will be set in loop
                'notification' => [
                    'title' => $title,
                    'body' => $body
                ],
                'data' => $data,
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'badge' => 1
                        ]
                    ]
                ]
            ]
        ];

        if ($this->useServiceAccount) {
            // Use OAuth 2.0 with Service Account (modern approach)
            $accessToken = $this->getAccessToken();
            $url = "https://fcm.googleapis.com/v1/projects/{$this->projectId}/messages:send";
            
            // Send to each token individually for v1 API
            $successCount = 0;
            $failureCount = 0;
            
            foreach ($tokens as $token) {
                $messagePayload = $payload;
                $messagePayload['message']['token'] = $token;
                
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json'
                ])->post($url, $messagePayload);
                
                if ($response->successful()) {
                    $successCount++;
                } else {
                    $failureCount++;
                    Log::error('FCM notification failed for token', [
                        'token' => $token,
                        'error' => $response->body()
                    ]);
                }
            }
            
            Log::info('FCM notification sent via Service Account', [
                'tokens_count' => count($tokens),
                'success' => $successCount,
                'failure' => $failureCount
            ]);
            
            return $failureCount === 0;
        } else {
            // Use legacy Server Key approach
            $legacyPayload = [
                'registration_ids' => $tokens,
                'notification' => $notification,
                'data' => $data,
                'priority' => 'high'
            ];

            $response = Http::withHeaders([
                'Authorization' => 'key=' . $this->serverKey,
                'Content-Type' => 'application/json'
            ])->post('https://fcm.googleapis.com/fcm/send', $legacyPayload);

            if ($response->successful()) {
                $result = $response->json();
                Log::info('FCM notification sent via Server Key', [
                    'tokens_count' => count($tokens),
                    'success' => $result['success'] ?? 0,
                    'failure' => $result['failure'] ?? 0
                ]);
                return true;
            } else {
                Log::error('FCM notification failed', [
                    'error' => $response->body()
                ]);
                return false;
            }
        }
    }
    
    /**
     * Get OAuth 2.0 access token for Service Account
     */
    private function getAccessToken()
    {
        $privateKey = str_replace('\\n', "\n", $this->privateKey);
        
        $jwtHeader = base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT']));
        
        $now = time();
        $jwtPayload = base64_encode(json_encode([
            'iss' => $this->clientEmail,
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now
        ]));
        
        $signature = '';
        openssl_sign($jwtHeader . '.' . $jwtPayload, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        $jwt = $jwtHeader . '.' . $jwtPayload . '.' . base64_encode($signature);
        
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt
        ]);
        
        if ($response->successful()) {
            return $response->json()['access_token'];
        }
        
        throw new \Exception('Failed to get access token: ' . $response->body());
    }

    /**
     * Send notification to single token
     */
    public function sendToToken(string $token, string $title, string $body, array $data = [])
    {
        return $this->sendNotification([$token], $title, $body, $data);
    }
}

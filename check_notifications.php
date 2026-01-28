<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== NOTIFICATION SYSTEM CHECK ===\n\n";

// 1. Check Device Tokens
echo "1. CHECKING DEVICE TOKENS:\n";
$tokens = App\Models\DeviceToken::with(['user', 'student'])->get();

if ($tokens->isEmpty()) {
    echo "❌ NO DEVICE TOKENS FOUND\n";
    echo "   - Make sure app is logged in\n";
    echo "   - Check internet connection\n";
    echo "   - Try clearing app data and login again\n";
} else {
    echo "✅ FOUND " . $tokens->count() . " DEVICE TOKEN(S):\n";
    foreach ($tokens as $token) {
        echo "   - User: " . $token->user->name . " (" . $token->user->email . ")\n";
        echo "   - Token: " . substr($token->fcm_token, 0, 30) . "...\n";
        echo "   - Device: " . $token->device_type . "\n";
        echo "   - Last Used: " . $token->last_used_at . "\n\n";
    }
}

echo "\n2. CHECKING FCM CONFIGURATION:\n";
$fcmProjectId = config('services.fcm.project_id');
$fcmPrivateKey = config('services.fcm.private_key');
$fcmClientEmail = config('services.fcm.client_email');
$fcmServerKey = config('services.fcm.server_key');

if (!empty($fcmProjectId) && !empty($fcmPrivateKey) && !empty($fcmClientEmail)) {
    echo "✅ FCM SERVICE ACCOUNT CONFIGURED (Modern Approach)\n";
    echo "   - Project ID: " . $fcmProjectId . "\n";
    echo "   - Client Email: " . $fcmClientEmail . "\n";
} elseif (!empty($fcmServerKey)) {
    echo "✅ FCM SERVER KEY CONFIGURED (Legacy Approach)\n";
} else {
    echo "❌ FCM NOT CONFIGURED\n";
    echo "   - Configure Service Account (recommended) or Server Key\n";
}

echo "\n3. CHECKING STUDENT ACCOUNTS:\n";
$students = App\Models\Student::with('user')->limit(5)->get();
foreach ($students as $student) {
    echo "   - " . $student->user->name . " (ID: " . $student->id . ")\n";
}

echo "\n4. TEST NOTIFICATION:\n";
if (!$tokens->isEmpty()) {
    $firstToken = $tokens->first();
    echo "Testing notification to: " . $firstToken->user->name . "\n";
    
    try {
        $fcmService = new App\Services\FcmNotificationService();
        
        // Test attendance notification
        $result = $fcmService->sendToToken(
            $firstToken->fcm_token,
            "Test Notification",
            "This is a test notification from your Student Management System",
            [
                'type' => 'test',
                'timestamp' => now()->toISOString()
            ]
        );
        
        if ($result) {
            echo "✅ TEST NOTIFICATION SENT SUCCESSFULLY\n";
            echo "   - Check your device now!\n";
        } else {
            echo "❌ TEST NOTIFICATION FAILED\n";
            echo "   - Check FCM server key\n";
            echo "   - Check device token validity\n";
        }
    } catch (Exception $e) {
        echo "❌ ERROR SENDING NOTIFICATION: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ CANNOT TEST - NO DEVICE TOKENS\n";
}

echo "\n5. TROUBLESHOOTING TIPS:\n";
echo "   - If no tokens: Clear app data → Login again\n";
echo "   - If notification not received: Check device notification permissions\n";
echo "   - If sending failed: Check FCM server key in .env\n";
echo "   - Check Android Studio Logcat for errors\n";

echo "\n=== CHECK COMPLETE ===\n";

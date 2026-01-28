<?php

echo "=== TESTING FCM SERVICE ACCOUNT ===\n\n";

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $fcmService = new App\Services\FcmNotificationService();
    
    // Get the newest device token (most recently used)
    $token = App\Models\DeviceToken::with(['user', 'student'])->orderBy('last_used_at', 'desc')->first();
    
    if (!$token) {
        echo "❌ No device tokens found\n";
        exit;
    }
    
    echo "📱 Testing notification to: {$token->user->name}\n";
    echo "🔑 Token: " . substr($token->fcm_token, 0, 30) . "...\n\n";
    
    // Test notification
    $result = $fcmService->sendToToken(
        $token->fcm_token,
        "Test Notification",
        "This is a test from FCM Service Account",
        [
            'type' => 'test',
            'timestamp' => now()->toISOString()
        ]
    );
    
    if ($result) {
        echo "✅ NOTIFICATION SENT SUCCESSFULLY!\n";
        echo "📱 Check your Android device now!\n";
    } else {
        echo "❌ NOTIFICATION FAILED\n";
        echo "🔍 Check Laravel logs for details\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>

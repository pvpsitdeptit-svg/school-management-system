<?php

echo "=== FCM APP STATE TESTS ===\n\n";

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $fcmService = new App\Services\FcmNotificationService();
    
    // Get the newest device token
    $token = App\Models\DeviceToken::with(['user', 'student'])->orderBy('last_used_at', 'desc')->first();
    
    if (!$token) {
        echo "❌ No device tokens found\n";
        exit;
    }
    
    echo "📱 Testing with: {$token->user->name}\n";
    echo "🔑 Token: " . substr($token->fcm_token, 0, 30) . "...\n\n";
    
    $testCases = [
        'app_closed' => [
            'title' => 'App Closed Test',
            'body' => 'Notification sent when app was closed',
            'data' => [
                'type' => 'test_app_closed',
                'scenario' => 'app_closed',
                'timestamp' => now()->toISOString()
            ]
        ],
        'app_background' => [
            'title' => 'App Background Test', 
            'body' => 'Notification sent when app was in background',
            'data' => [
                'type' => 'test_app_background',
                'scenario' => 'app_background',
                'timestamp' => now()->toISOString()
            ]
        ],
        'app_foreground' => [
            'title' => 'App Foreground Test',
            'body' => 'Silent notification for foreground app',
            'data' => [
                'type' => 'test_app_foreground',
                'scenario' => 'app_foreground',
                'silent' => 'true',
                'timestamp' => now()->toISOString()
            ]
        ]
    ];
    
    echo "🧪 Available Test Scenarios:\n\n";
    
    foreach ($testCases as $key => $test) {
        echo "📋 {$key}: {$test['title']}\n";
        echo "   Message: {$test['body']}\n";
        echo "   Data: " . json_encode($test['data'], JSON_PRETTY_PRINT) . "\n\n";
    }
    
    echo "🎯 How to Test Each Scenario:\n\n";
    
    echo "1️⃣ APP CLOSED TEST:\n";
    echo "   • Close the app completely (swipe from recent apps)\n";
    echo "   • Run: php test_app_states.php app_closed\n";
    echo "   • Expected: Notification should appear in system tray\n\n";
    
    echo "2️⃣ APP BACKGROUND TEST:\n";
    echo "   • Open app, then press home button (app in background)\n";
    echo "   • Run: php test_app_states.php app_background\n";
    echo "   • Expected: Notification should appear in system tray\n\n";
    
    echo "3️⃣ APP FOREGROUND TEST:\n";
    echo "   • Keep app open and visible on screen\n";
    echo "   • Run: php test_app_states.php app_foreground\n";
    echo "   • Expected: Silent handling or in-app banner\n\n";
    
    // Check if specific test requested
    if ($argc > 1 && isset($testCases[$argv[1]])) {
        $scenario = $argv[1];
        $test = $testCases[$scenario];
        
        echo "🚀 Running {$scenario} test...\n";
        echo "📤 Sending: {$test['title']}\n\n";
        
        $result = $fcmService->sendToToken(
            $token->fcm_token,
            $test['title'],
            $test['body'],
            $test['data']
        );
        
        if ($result) {
            echo "✅ NOTIFICATION SENT SUCCESSFULLY!\n";
            echo "📱 Check your device for the notification\n";
        } else {
            echo "❌ NOTIFICATION FAILED\n";
            echo "🔍 Check Laravel logs for details\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>

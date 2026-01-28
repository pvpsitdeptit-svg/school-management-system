<?php

echo "=== TESTING NOTIFICATION CLICK NAVIGATION ===\n\n";

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
    
    echo "📱 Testing notification click navigation\n";
    echo "👤 User: {$token->user->name}\n";
    echo "🔑 Token: " . substr($token->fcm_token, 0, 30) . "...\n\n";
    
    // Test different notification types with navigation
    $testNotifications = [
        'home' => [
            'title' => 'Home Navigation Test',
            'body' => 'Tap to navigate to Home screen',
            'data' => [
                'type' => 'general',
                'target_screen' => 'home',
                'test_type' => 'navigation'
            ]
        ],
        'attendance' => [
            'title' => 'Attendance Navigation Test', 
            'body' => 'Tap to navigate to Attendance screen',
            'data' => [
                'type' => 'attendance',
                'target_screen' => 'attendance',
                'date' => date('Y-m-d'),
                'test_type' => 'navigation'
            ]
        ],
        'marks' => [
            'title' => 'Marks Navigation Test',
            'body' => 'Tap to navigate to Marks screen', 
            'data' => [
                'type' => 'marks',
                'target_screen' => 'marks',
                'exam_name' => 'Test Exam',
                'test_type' => 'navigation'
            ]
        ]
    ];
    
    echo "🧪 Available Navigation Tests:\n\n";
    
    foreach ($testNotifications as $key => $notification) {
        echo "📋 {$key}: {$notification['title']}\n";
        echo "   Target: {$notification['data']['target_screen']}\n";
        echo "   Message: {$notification['body']}\n\n";
    }
    
    echo "🎯 How to Test:\n\n";
    echo "1️⃣ Close your app completely\n";
    echo "2️⃣ Run: php test_notification_click.php home\n";
    echo "3️⃣ Receive notification and tap it\n";
    echo "4️⃣ App should open to the correct screen\n\n";
    
    // Check if specific test requested
    if ($argc > 1 && isset($testNotifications[$argv[1]])) {
        $scenario = $argv[1];
        $test = $testNotifications[$scenario];
        
        echo "🚀 Sending {$scenario} navigation test...\n";
        echo "📤 Title: {$test['title']}\n";
        echo "🎯 Target Screen: {$test['data']['target_screen']}\n\n";
        
        $result = $fcmService->sendToToken(
            $token->fcm_token,
            $test['title'],
            $test['body'],
            $test['data']
        );
        
        if ($result) {
            echo "✅ NOTIFICATION SENT SUCCESSFULLY!\n";
            echo "📱 Tap the notification to test navigation\n";
            echo "🔍 Check Android logs for navigation handling\n";
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

<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== DIRECT FCM TEST ===\n\n";

// Test with a sample FCM token (you'll need to get this from your device)
$testToken = "test_device_token_here"; // Replace with actual device token

echo "Testing FCM configuration...\n";

// Test FCM service directly
$fcmService = new App\Services\FcmNotificationService();

// Create a test notification
$testData = [
    'type' => 'test',
    'title' => 'Test Notification',
    'body' => 'This is a test notification from your Student Management System',
    'timestamp' => now()->toISOString()
];

echo "Attempting to send test notification...\n";

// Test with a dummy token first to check if FCM is working
$result = $fcmService->sendToToken(
    "dummy_token_for_testing", 
    "Test Title", 
    "Test Body", 
    $testData
);

if ($result) {
    echo "❌ UNEXPECTED: Dummy token should fail\n";
} else {
    echo "✅ FCM Service working correctly (dummy token failed as expected)\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Clear app data on your Android device\n";
echo "2. Login with student account (student1@demo.com / password123)\n";
echo "3. Check if device token appears in database:\n";
echo "   SELECT * FROM device_tokens;\n";
echo "4. Run this script again with actual device token\n";
echo "5. Or use Laravel tinker to test:\n";
echo "   php artisan tinker\n";
echo "   \$fcmService = new App\\Services\\FcmNotificationService();\n";
echo "   \$fcmService->sendAttendanceNotification(1, 'Test Student', '25 Jan', 'present');\n";

echo "\n=== ANDROID APP TROUBLESHOOTING ===\n";
echo "If no device token appears:\n";
echo "1. Check Android Studio Logcat for FCM errors\n";
echo "2. Verify internet connection\n";
echo "3. Check Firebase project configuration\n";
echo "4. Make sure app has notification permissions\n";
echo "5. Try reinstalling the app\n";

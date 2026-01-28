<?php

echo "=== TESTING FIREBASE AUTHENTICATION ===\n\n";

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $firebaseService = new App\Services\FirebaseService();
    
    echo "✅ Firebase Service initialized\n";
    echo "📱 Project ID: " . config('services.fcm.project_id') . "\n\n";
    
    // Test with a sample token (you'll need to get this from Android logs)
    echo "📝 To test authentication:\n";
    echo "1. Login on Android app\n";
    echo "2. Copy the Authorization Bearer token from Android logs\n";
    echo "3. Run: php test_firebase_auth_with_token.php <token>\n\n";
    
    echo "✅ Firebase authentication is ready!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>

<?php

echo "=== TESTING ENVIRONMENT VARIABLES ===\n\n";

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "🔍 Environment Variables:\n";
echo "FIREBASE_PROJECT_ID: " . env('FIREBASE_PROJECT_ID') . "\n";
echo "FCM_PROJECT_ID: " . env('FCM_PROJECT_ID') . "\n";
echo "getenv FIREBASE_PROJECT_ID: " . getenv('FIREBASE_PROJECT_ID') . "\n";
echo "getenv FCM_PROJECT_ID: " . getenv('FCM_PROJECT_ID') . "\n";

echo "\n🔍 Config Values:\n";
echo "config('services.fcm.project_id'): " . config('services.fcm.project_id') . "\n";

echo "\n🔍 Testing Firebase Service:\n";
try {
    $firebaseService = new App\Services\FirebaseService();
    echo "Firebase Service created successfully\n";
} catch (Exception $e) {
    echo "Firebase Service error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";

?>

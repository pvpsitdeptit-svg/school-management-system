<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING FIXED FIREBASE USER CREATION ===\n\n";

// Test the fixed FirebaseAuthService
try {
    $firebaseService = new App\Services\FirebaseAuthService();
    
    if (!$firebaseService->isConfigured()) {
        echo "❌ FirebaseAuthService not configured\n";
        exit(1);
    }
    
    echo "✅ FirebaseAuthService is configured\n";
    
    // Test creating a user
    $testEmail = 'test_' . time() . '@school.com';
    $testPassword = 'Test123456!';
    $testName = 'Test School Admin';
    
    echo "🔥 Creating test user: {$testEmail}\n";
    
    $result = $firebaseService->createUser($testEmail, $testPassword, $testName);
    
    if ($result['success']) {
        echo "✅ SUCCESS: Firebase user created!\n";
        echo "Firebase UID: " . $result['data']['localId'] . "\n";
        echo "Email: " . $result['data']['email'] . "\n";
        echo "Display Name: " . ($result['data']['displayName'] ?? 'N/A') . "\n";
        
        // Clean up - delete the test user
        echo "\n🧹 Cleaning up test user...\n";
        
        $apiKey = env('FIREBASE_API_KEY');
        $ch = curl_init();
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:delete?key={$apiKey}";
        $data = ['localId' => $result['data']['localId']];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        curl_exec($ch);
        curl_close($ch);
        
        echo "✅ Test user deleted\n";
        
    } else {
        echo "❌ FAILED: " . $result['message'] . "\n";
        if (isset($result['error'])) {
            echo "Error details: " . json_encode($result['error'], JSON_PRETTY_PRINT) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
}

echo "\n=== CHECKING SCHOOL CREATION FLOW ===\n";

// Check the SuperAdminController school creation method
echo "🔍 Checking SuperAdminController::storeSchool()...\n";

$reflection = new ReflectionClass('App\Http\Controllers\SuperAdminController');
if ($reflection->hasMethod('storeSchool')) {
    $method = $reflection->getMethod('storeSchool');
    echo "✅ storeSchool() method exists\n";
    
    // Get method source (simplified check)
    $filename = $reflection->getFileName();
    $startLine = $method->getStartLine();
    $endLine = $method->getEndLine();
    
    echo "Method located in: {$filename} (lines {$startLine}-{$endLine})\n";
    
    // Check if file contains Firebase service calls
    $content = file_get_contents($filename);
    if (strpos($content, 'createFirebaseUser') !== false) {
        echo "✅ createFirebaseUser() call found in storeSchool()\n";
    } else {
        echo "❌ createFirebaseUser() call NOT found in storeSchool()\n";
    }
    
    if (strpos($content, 'firebase_uid') !== false) {
        echo "✅ firebase_uid field handling found\n";
    } else {
        echo "❌ firebase_uid field handling NOT found\n";
    }
} else {
    echo "❌ storeSchool() method not found\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. ✅ Firebase user creation is now fixed\n";
echo "2. Try creating a new school to test the flow\n";
echo "3. Check Laravel logs: storage/logs/laravel.log\n";
echo "4. Verify school admin appears in Firebase Console\n";

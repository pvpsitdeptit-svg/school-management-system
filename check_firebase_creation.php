<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING FIREBASE USER CREATION ===\n\n";

// Get Firebase configuration
$apiKey = env('FIREBASE_API_KEY');
$projectId = env('FIREBASE_PROJECT_ID');

echo "Firebase API Key: " . ($apiKey ? 'SET' : 'NOT SET') . "\n";
echo "Firebase Project ID: " . ($projectId ?? 'NOT SET') . "\n\n";

if (!$apiKey || !$projectId) {
    echo "❌ Firebase credentials not configured!\n";
    exit(1);
}

// Test Firebase user creation
echo "🔥 Testing Firebase user creation...\n";

$testEmail = 'test_' . time() . '@example.com';
$testPassword = 'Test123456!';
$testName = 'Test User';

echo "Creating test user: {$testEmail}\n";

$ch = curl_init();
$url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts?key={$apiKey}";
$data = [
    'email' => $testEmail,
    'password' => $testPassword,
    'displayName' => $testName,
    'returnSecureToken' => false
];

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode === 200 && isset($result['localId'])) {
    echo "✅ SUCCESS: Firebase user created!\n";
    echo "Firebase UID: " . $result['localId'] . "\n";
    echo "Email: " . $result['email'] . "\n";
    
    // Now test if our FirebaseAuthService works
    echo "\n🔧 Testing FirebaseAuthService...\n";
    
    try {
        $firebaseService = new App\Services\FirebaseAuthService();
        $serviceResult = $firebaseService->createUser($testEmail, $testPassword, $testName);
        
        if ($serviceResult['success']) {
            echo "✅ FirebaseAuthService working correctly\n";
            echo "Service returned UID: " . $serviceResult['data']['localId'] . "\n";
        } else {
            echo "❌ FirebaseAuthService failed: " . $serviceResult['message'] . "\n";
        }
    } catch (Exception $e) {
        echo "❌ FirebaseAuthService error: " . $e->getMessage() . "\n";
    }
    
    // Clean up - delete the test user
    echo "\n🧹 Cleaning up test user...\n";
    $ch = curl_init();
    $url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts:delete?key={$apiKey}";
    $data = ['localId' => $result['localId']];
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    curl_exec($ch);
    curl_close($ch);
    
    echo "✅ Test user deleted\n";
    
} else {
    echo "❌ Firebase user creation failed:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== CHECKING SCHOOL ADMIN CREATION ===\n";

// Check recent school admins in database
$schoolAdmins = App\Models\User::where('role', 'school_admin')
    ->orderBy('created_at', 'desc')
    ->limit(3)
    ->get(['id', 'name', 'email', 'firebase_uid', 'created_at']);

echo "Recent school admins in database:\n";
foreach ($schoolAdmins as $admin) {
    echo "- {$admin->email} (UID: " . ($admin->firebase_uid ?? 'NULL') . ")\n";
    echo "  Created: {$admin->created_at}\n";
    
    // Check if this user exists in Firebase
    if ($admin->firebase_uid && $admin->firebase_uid !== 'legacy_12_needs_firebase_setup' && $admin->firebase_uid !== 'legacy_13_needs_firebase_setup') {
        echo "  🔍 Checking Firebase...\n";
        
        $ch = curl_init();
        $url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts:lookup?key={$apiKey}";
        $data = ['localId' => $admin->firebase_uid];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if ($httpCode === 200 && isset($result['users'][0])) {
            echo "  ✅ Found in Firebase: " . $result['users'][0]['email'] . "\n";
        } else {
            echo "  ❌ NOT found in Firebase!\n";
        }
    } else {
        echo "  ⚠️  Legacy or NULL Firebase UID\n";
    }
    echo "\n";
}

echo "=== RECOMMENDATIONS ===\n";
echo "1. If Firebase user creation is working, check SuperAdminController::storeSchool()\n";
echo "2. Make sure FirebaseAuthService is being called correctly\n";
echo "3. Check Laravel logs for Firebase creation errors\n";
echo "4. Verify the school creation flow completes without errors\n";

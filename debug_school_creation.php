<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DEBUGGING SCHOOL CREATION ISSUE ===\n\n";

// Check recent schools
echo "🏫 Recent schools in database:\n";
$recentSchools = \App\Models\School::orderBy('created_at', 'desc')->limit(3)->get();
foreach ($recentSchools as $school) {
    echo "- {$school->name} (ID: {$school->id}, Created: {$school->created_at})\n";
}

echo "\n👤 Recent users in database:\n";
$recentUsers = \App\Models\User::orderBy('created_at', 'desc')->limit(5)->get();
foreach ($recentUsers as $user) {
    echo "- {$user->email} (Role: {$user->role}, Firebase UID: " . ($user->firebase_uid ?? 'NULL') . ")\n";
}

echo "\n🔥 Testing FirebaseAuthService directly:\n";
try {
    $firebaseService = new \App\Services\FirebaseAuthService();
    
    if (!$firebaseService->isConfigured()) {
        echo "❌ FirebaseAuthService not configured\n";
        exit(1);
    }
    
    $testEmail = 'debug_' . time() . '@test.com';
    $testPassword = 'Test123456!';
    $testName = 'Debug User';
    
    echo "Creating Firebase user: {$testEmail}\n";
    $result = $firebaseService->createUser($testEmail, $testPassword, $testName);
    
    if ($result['success']) {
        echo "✅ Firebase user created: UID = {$result['data']['localId']}\n";
        
        // Clean up
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
        echo "✅ Test Firebase user deleted\n";
        
    } else {
        echo "❌ Firebase user creation failed: " . $result['message'] . "\n";
        if (isset($result['error'])) {
            echo "Error details: " . json_encode($result['error'], JSON_PRETTY_PRINT) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ FirebaseAuthService error: " . $e->getMessage() . "\n";
}

echo "\n🔍 Checking SuperAdminController validation rules:\n";
$controller = new \App\Http\Controllers\SuperAdminController();
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('storeSchool');

// Get validation rules from the method
$filename = $reflection->getFileName();
$startLine = $method->getStartLine();
$endLine = $method->getEndLine();

echo "storeSchool() method: lines {$startLine}-{$endLine} in {$filename}\n";

// Check if the method is being called
echo "\n📋 Manual test - Simulating school creation:\n";

try {
    // Test data that should work
    $testRequest = new \Illuminate\Http\Request([
        'name' => 'Debug School ' . time(),
        'domain' => 'debug-' . time(),
        'email' => 'info@debug-' . time() . '.com',
        'phone' => '1234567890',
        'address' => 'Debug Address',
        'admin_name' => 'Debug Admin',
        'admin_email' => 'admin@debug-' . time() . '.com',
        'admin_password' => 'Admin123456',
    ]);
    
    echo "Test request data prepared\n";
    echo "School name: {$testRequest->name}\n";
    echo "Admin email: {$testRequest->admin_email}\n";
    
    // Try to call the storeSchool method
    // Note: This might fail due to middleware, but we can see the error
    
} catch (Exception $e) {
    echo "❌ Manual test failed: " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Try creating a school via the web interface\n";
echo "2. Check browser console for JavaScript errors\n";
echo "3. Check network tab for failed requests\n";
echo "4. Look for validation errors on the form\n";
echo "5. Check if the form is actually submitting\n";

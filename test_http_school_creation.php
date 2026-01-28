<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING SCHOOL CREATION VIA HTTP ===\n\n";

// Test the actual school creation endpoint
echo "🌐 Testing school creation endpoint...\n";

try {
    // Create a mock request
    $request = \Illuminate\Http\Request::create(
        '/super-admin/schools',
        'POST',
        [
            'name' => 'HTTP Test School ' . time(),
            'domain' => 'http-test-' . time(),
            'email' => 'info@httptest.com',
            'phone' => '1234567890',
            'address' => 'HTTP Test Address',
            'admin_name' => 'HTTP Test Admin',
            'admin_email' => 'admin@httptest.com',
            'admin_password' => 'Admin123456',
            '_token' => csrf_token(), // Add CSRF token
        ]
    );
    
    echo "Request created with data:\n";
    echo "- School: {$request->name}\n";
    echo "- Admin: {$request->admin_email}\n";
    echo "- Domain: {$request->domain}\n";
    
    // Get the SuperAdminController
    $controller = app(\App\Http\Controllers\SuperAdminController::class);
    
    echo "\n🔧 Calling storeSchool method...\n";
    
    // Call the method
    $response = $controller->storeSchool($request);
    
    echo "✅ Method called successfully\n";
    echo "Response type: " . get_class($response) . "\n";
    
    if (method_exists($response, 'getTargetUrl')) {
        echo "Redirect URL: " . $response->getTargetUrl() . "\n";
    }
    
    if (method_exists($response, 'getSession')) {
        $session = $response->getSession();
        if ($session->has('success')) {
            echo "Success message: " . $session->get('success') . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ HTTP test failed: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "\n🔍 Checking results in database:\n";

// Check if school was created
$latestSchool = \App\Models\School::orderBy('created_at', 'desc')->first();
if ($latestSchool) {
    echo "Latest school: {$latestSchool->name} (ID: {$latestSchool->id})\n";
    echo "Created: {$latestSchool->created_at}\n";
}

// Check if user was created
$latestUser = \App\Models\User::orderBy('created_at', 'desc')->first();
if ($latestUser) {
    echo "Latest user: {$latestUser->email} (Role: {$latestUser->role})\n";
    echo "Firebase UID: " . ($latestUser->firebase_uid ?? 'NULL') . "\n";
    echo "School ID: " . ($latestUser->school_id ?? 'NULL') . "\n";
    echo "Created: {$latestUser->created_at}\n";
}

echo "\n=== CLEANUP ===\n";

// Clean up test data if created
if (isset($latestSchool) && strpos($latestSchool->name, 'HTTP Test School') !== false) {
    echo "🧹 Cleaning up test school...\n";
    \App\Models\School::destroy($latestSchool->id);
}

if (isset($latestUser) && strpos($latestUser->email, 'httptest') !== false) {
    echo "🧹 Cleaning up test user...\n";
    if ($latestUser->firebase_uid) {
        // Delete from Firebase
        $apiKey = env('FIREBASE_API_KEY');
        $ch = curl_init();
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:delete?key={$apiKey}";
        $data = ['localId' => $latestUser->firebase_uid];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        curl_exec($ch);
        curl_close($ch);
    }
    \App\Models\User::destroy($latestUser->id);
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Try creating a school via the web interface\n";
echo "2. Check browser network tab for request/response\n";
echo "3. Look for JavaScript errors in browser console\n";
echo "4. Check if form validation is passing\n";

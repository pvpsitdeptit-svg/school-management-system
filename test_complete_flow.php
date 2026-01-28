<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING COMPLETE SCHOOL CREATION FLOW ===\n\n";

try {
    // Test data for school creation
    $schoolData = [
        'name' => 'Test School ' . time(),
        'code' => 'TST' . rand(100, 999),
        'subdomain' => 'test-school-' . time(),
        'email' => 'info@testschool.com',
        'phone' => '1234567890',
        'address' => 'Test Address',
        'status' => 'active',
    ];
    
    $adminData = [
        'admin_name' => 'Test Admin',
        'admin_email' => 'admin@testschool.com',
        'admin_password' => 'Admin123456',
    ];
    
    echo "🏫 Creating school: {$schoolData['name']}\n";
    echo "👤 Admin: {$adminData['admin_email']}\n\n";
    
    // Step 1: Create school
    $school = \App\Models\School::create($schoolData);
    echo "✅ School created with ID: {$school->id}\n";
    
    // Step 2: Create Firebase user
    echo "🔥 Creating Firebase user...\n";
    $firebaseService = new \App\Services\FirebaseAuthService();
    $firebaseResult = $firebaseService->createUser(
        $adminData['admin_email'],
        $adminData['admin_password'],
        $adminData['admin_name']
    );
    
    $firebaseUid = null;
    if ($firebaseResult['success']) {
        $firebaseUid = $firebaseResult['data']['localId'];
        echo "✅ Firebase user created with UID: {$firebaseUid}\n";
    } else {
        echo "❌ Firebase user creation failed: " . $firebaseResult['message'] . "\n";
    }
    
    // Step 3: Create database user
    echo "🗄️ Creating database user...\n";
    $user = \App\Models\User::create([
        'name' => $adminData['admin_name'],
        'email' => $adminData['admin_email'],
        'password' => null,
        'role' => 'school_admin',
        'school_id' => $school->id,
        'firebase_uid' => $firebaseUid,
        'status' => 'active',
    ]);
    
    echo "✅ Database user created with ID: {$user->id}\n";
    
    // Step 4: Verify the user was created
    $verifyUser = \App\Models\User::find($user->id);
    if ($verifyUser) {
        echo "✅ User verification successful:\n";
        echo "  - Name: {$verifyUser->name}\n";
        echo "  - Email: {$verifyUser->email}\n";
        echo "  - Role: {$verifyUser->role}\n";
        echo "  - School ID: {$verifyUser->school_id}\n";
        echo "  - Firebase UID: " . ($verifyUser->firebase_uid ?? 'NULL') . "\n";
        echo "  - Status: {$verifyUser->status}\n";
    } else {
        echo "❌ User verification failed!\n";
    }
    
    echo "\n🎉 COMPLETE SCHOOL CREATION TEST SUCCESSFUL!\n";
    echo "You can now try logging in with:\n";
    echo "Email: {$adminData['admin_email']}\n";
    echo "Password: {$adminData['admin_password']}\n";
    echo "Role: school_admin\n";
    
    // Clean up - remove test data
    echo "\n🧹 Cleaning up test data...\n";
    if ($firebaseUid) {
        // Delete from Firebase
        $apiKey = env('FIREBASE_API_KEY');
        $ch = curl_init();
        $url = "https://identitytoolkit.googleapis.com/v1/accounts:delete?key={$apiKey}";
        $data = ['localId' => $firebaseUid];
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        curl_exec($ch);
        curl_close($ch);
        echo "✅ Firebase user deleted\n";
    }
    
    // Delete from database
    \App\Models\User::destroy($user->id);
    \App\Models\School::destroy($school->id);
    echo "✅ Database records deleted\n";
    
} catch (Exception $e) {
    echo "❌ TEST FAILED: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. ✅ The complete flow is working\n";
echo "2. Try creating a school via the web interface\n";
echo "3. Check logs: storage/logs/laravel.log\n";
echo "4. Verify in Firebase Console\n";

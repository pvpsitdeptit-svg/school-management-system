<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING FIREBASE CONFIGURATION ===\n";

// Test with your actual Firebase credentials
$apiKey = "AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw";
$projectId = "studentmanagementsystem-74f48";

echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "Project ID: {$projectId}\n\n";

// Test user lookup
echo "Testing Firebase user lookup for unnikiranj@gmail.com...\n";
$ch = curl_init();
$url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts:lookup?key={$apiKey}";
$data = ['email' => 'unnikiranj@gmail.com'];

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
    echo "✅ SUCCESS: User found in Firebase!\n";
    echo "Firebase UID: " . $result['users'][0]['localId'] . "\n";
    echo "Email: " . $result['users'][0]['email'] . "\n";
    echo "Display Name: " . ($result['users'][0]['displayName'] ?? 'N/A') . "\n";
    
    // Check database
    $dbUser = App\Models\User::where('email', 'unnikiranj@gmail.com')->first();
    if ($dbUser) {
        echo "\nDatabase Check:\n";
        echo "Database UID: " . ($dbUser->firebase_uid ?? 'NULL') . "\n";
        if ($dbUser->firebase_uid === $result['users'][0]['localId']) {
            echo "✅ UIDs MATCH - Authentication should work!\n";
        } else {
            echo "❌ UIDs don't match - Need to update database\n";
            // Update database with correct UID
            $dbUser->firebase_uid = $result['users'][0]['localId'];
            $dbUser->save();
            echo "✅ Database updated with correct Firebase UID\n";
        }
    }
} else {
    echo "❌ User lookup failed:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== NEXT STEPS ===\n";
echo "1. Add these to your .env file:\n\n";
echo "FIREBASE_API_KEY=AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw\n";
echo "FIREBASE_PROJECT_ID=studentmanagementsystem-74f48\n";
echo "FIREBASE_AUTH_DOMAIN=studentmanagementsystem-74f48.firebaseapp.com\n\n";
echo "2. Run: php artisan cache:clear\n";
echo "3. Try login at: http://localhost:8080/login\n";
echo "4. Use: unnikiranj@gmail.com / 12345678\n";

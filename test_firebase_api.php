<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== TESTING FIREBASE TOKEN VERIFICATION ===\n";

// Test with your actual Firebase credentials
$apiKey = "AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw";
$projectId = "studentmanagementsystem-74f48";

echo "API Key: " . substr($apiKey, 0, 10) . "...\n";
echo "Project ID: {$projectId}\n\n";

// Test token verification endpoint (this should work)
echo "Testing Firebase token verification endpoint...\n";
$ch = curl_init();
$url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}:lookup?key={$apiKey}";

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($httpCode === 200) {
    echo "✅ SUCCESS: Firebase API key is valid!\n";
    echo "Token verification endpoint is accessible.\n";
    
    echo "\n=== ADD THESE TO YOUR .env FILE ===\n\n";
    echo "FIREBASE_API_KEY=AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw\n";
    echo "FIREBASE_PROJECT_ID=studentmanagementsystem-74f48\n";
    echo "FIREBASE_AUTH_DOMAIN=studentmanagementsystem-74f48.firebaseapp.com\n";
    echo "FIREBASE_DATABASE_URL=https://studentmanagementsystem-74f48-default-rtdb.firebaseio.com\n";
    echo "FIREBASE_STORAGE_BUCKET=studentmanagementsystem-74f48.firebasestorage.app\n";
    echo "FIREBASE_MESSAGING_SENDER_ID=986803646077\n";
    echo "FIREBASE_APP_ID=1:986803646077:web:3a493bdcc8d418e0914b14\n";
    echo "FIREBASE_MEASUREMENT_ID=G-64F42VE501\n\n";
    
    echo "=== NEXT STEPS ===\n";
    echo "1. Add the above to your .env file\n";
    echo "2. Run: php artisan cache:clear\n";
    echo "3. Try login at: http://localhost:8080/login\n";
    echo "4. Use: unnikiranj@gmail.com / 12345678\n";
    echo "5. Should work now! 🎉\n";
} else {
    echo "❌ Firebase API test failed:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
}

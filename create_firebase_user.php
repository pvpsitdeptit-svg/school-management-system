<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check Firebase configuration
echo "=== FIREBASE CONFIGURATION ===\n";
echo "FIREBASE_API_KEY: " . (env('FIREBASE_API_KEY') ?: 'NOT SET') . "\n";
echo "FIREBASE_PROJECT_ID: " . (env('FIREBASE_PROJECT_ID') ?: 'NOT SET') . "\n";
echo "FIREBASE_AUTH_DOMAIN: " . (env('FIREBASE_AUTH_DOMAIN') ?: 'NOT SET') . "\n";
echo "\n";

// Try to create user in Firebase
$apiKey = env('FIREBASE_API_KEY');
$projectId = env('FIREBASE_PROJECT_ID');

if (!$apiKey || !$projectId) {
    echo "❌ Firebase credentials not configured!\n";
    echo "Please add these to your .env file:\n";
    echo "FIREBASE_API_KEY=your-api-key-here\n";
    echo "FIREBASE_PROJECT_ID=your-project-id\n";
    exit(1);
}

echo "🔥 Creating Firebase user for unnikiranj@gmail.com...\n";

// Create user in Firebase
$ch = curl_init();
$url = "https://identitytoolkit.googleapis.com/v1/projects/{$projectId}/accounts?key={$apiKey}";
$data = [
    'email' => 'unnikiranj@gmail.com',
    'password' => '12345678',
    'displayName' => 'Super Admin',
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
    echo "✅ Firebase user created successfully!\n";
    echo "Firebase UID: " . $result['localId'] . "\n";
    
    // Update database with correct Firebase UID
    $user = App\Models\User::where('email', 'unnikiranj@gmail.com')->first();
    if ($user) {
        $user->firebase_uid = $result['localId'];
        $user->save();
        echo "✅ Database updated with Firebase UID\n";
    }
} else {
    echo "❌ Firebase user creation failed:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
    
    // Check if user already exists
    if (isset($result['error']['message']) && strpos($result['error']['message'], 'email exists') !== false) {
        echo "ℹ️  User already exists in Firebase. Trying to get existing user info...\n";
        
        // Try to get user info
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
        
        if ($httpCode === 200 && isset($result['users'][0]['localId'])) {
            echo "✅ Found existing Firebase user!\n";
            echo "Firebase UID: " . $result['users'][0]['localId'] . "\n";
            
            // Update database with correct Firebase UID
            $user = App\Models\User::where('email', 'unnikiranj@gmail.com')->first();
            if ($user) {
                $user->firebase_uid = $result['users'][0]['localId'];
                $user->save();
                echo "✅ Database updated with Firebase UID\n";
            }
        }
    }
}

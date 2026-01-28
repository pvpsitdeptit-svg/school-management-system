<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== FIREBASE CONFIGURATION CHECK ===\n";
echo "FIREBASE_API_KEY: " . (env('FIREBASE_API_KEY') ? 'SET' : 'NOT SET') . "\n";
echo "FIREBASE_PROJECT_ID: " . (env('FIREBASE_PROJECT_ID') ? 'SET' : 'NOT SET') . "\n";
echo "FIREBASE_AUTH_DOMAIN: " . (env('FIREBASE_AUTH_DOMAIN') ? 'SET' : 'NOT SET') . "\n";

if (!env('FIREBASE_API_KEY') || !env('FIREBASE_PROJECT_ID')) {
    echo "\n❌ Firebase credentials not configured!\n";
    echo "Please add these to your .env file:\n\n";
    echo "FIREBASE_API_KEY=your-api-key-here\n";
    echo "FIREBASE_PROJECT_ID=your-project-id\n";
    echo "FIREBASE_AUTH_DOMAIN=your-project.firebaseapp.com\n\n";
    echo "You can get these from:\n";
    echo "1. Firebase Console → Project Settings → General\n";
    echo "2. Firebase Console → Project Settings → Your Apps → Web App\n";
    exit(1);
}

echo "\n✅ Firebase credentials are configured\n";

// Test Firebase token verification
echo "\n=== TESTING FIREBASE TOKEN VERIFICATION ===\n";

$apiKey = env('FIREBASE_API_KEY');
$projectId = env('FIREBASE_PROJECT_ID');

// First, try to get user info (this should work)
echo "Testing user lookup for unnikiranj@gmail.com...\n";
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
    echo "✅ User found in Firebase!\n";
    echo "Firebase UID: " . $result['users'][0]['localId'] . "\n";
    echo "Email: " . $result['users'][0]['email'] . "\n";
    
    // Check if UID matches database
    $dbUser = App\Models\User::where('email', 'unnikiranj@gmail.com')->first();
    if ($dbUser) {
        echo "Database UID: " . ($dbUser->firebase_uid ?? 'NULL') . "\n";
        if ($dbUser->firebase_uid === $result['users'][0]['localId']) {
            echo "✅ UIDs match!\n";
        } else {
            echo "❌ UIDs don't match!\n";
        }
    }
} else {
    echo "❌ User lookup failed:\n";
    echo "HTTP Code: {$httpCode}\n";
    echo "Response: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
}

echo "\n=== DATABASE USER CHECK ===\n";
$user = App\Models\User::where('email', 'unnikiranj@gmail.com')->first();
if ($user) {
    echo "✅ User found in database:\n";
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Firebase UID: " . ($user->firebase_uid ?? 'NULL') . "\n";
    echo "Status: {$user->status}\n";
} else {
    echo "❌ User not found in database!\n";
}

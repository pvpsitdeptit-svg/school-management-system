<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== TESTING DEVICE TOKEN API ===\n\n";

// Test data
$testData = [
    'fcm_token' => 'test_fcm_token_' . time(),
    'device_type' => 'android'
];

echo "1. Testing POST /api/device-token endpoint...\n";
echo "URL: http://192.168.0.105:8000/api/device-token\n";
echo "Data: " . json_encode($testData) . "\n\n";

// Use cURL to test the endpoint
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'http://192.168.0.105:8000/api/device-token');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'Accept: application/json'
]);

echo "Sending request...\n";
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($error) {
    echo "❌ cURL Error: $error\n";
} else {
    echo "✅ Response Code: $httpCode\n";
    echo "Response: $response\n\n";
}

echo "=== TROUBLESHOOTING TIPS ===\n";
echo "1. Make sure Laravel server is running: php artisan serve --host=0.0.0.0 --port=8000\n";
echo "2. Check firewall settings on port 8000\n";
echo "3. Verify device and PC are on same WiFi network\n";
echo "4. Test the endpoint in browser: http://192.168.0.105:8000/api/health\n";
echo "5. Check Laravel logs: storage/logs/laravel.log\n";

echo "\n=== NEXT STEPS ===\n";
echo "1. Rebuild Android app with increased timeouts\n";
echo "2. Clear app data and login again\n";
echo "3. Check Android logs for FCM token registration\n";

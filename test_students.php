<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

echo "=== TEST STUDENTS ===\n";

$students = App\Models\Student::with('user')->get();

foreach ($students as $student) {
    echo "Name: " . $student->user->name . "\n";
    echo "Email: " . $student->user->email . "\n";
    echo "Admission No: " . $student->admission_no . "\n";
    echo "Class: " . $student->schoolClass->name . " - " . $student->schoolClass->section . "\n";
    echo "Password: password123\n";
    echo "-------------------\n";
}

echo "\n=== DEVICE TOKENS ===\n";

$tokens = App\Models\DeviceToken::with('user')->get();

foreach ($tokens as $token) {
    echo "User: " . $token->user->name . "\n";
    echo "FCM Token: " . substr($token->fcm_token, 0, 20) . "...\n";
    echo "Device Type: " . $token->device_type . "\n";
    echo "Last Used: " . $token->last_used_at . "\n";
    echo "-------------------\n";
}

echo "\n=== TESTING INSTRUCTIONS ===\n";
echo "1. Install APK on 2 devices\n";
echo "2. Login with the student accounts above\n";
echo "3. Check device_tokens table for registration\n";
echo "4. Test attendance notifications\n";
echo "5. Test exam results notifications\n";
echo "6. Test notification click navigation\n";

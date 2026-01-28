<?php

echo "=== CLEANING .env WITH YOUR FIREBASE CREDENTIALS ===\n\n";

$envFile = '.env';
$backupFile = '.env.backup.' . date('Y-m-d_H-i-s');

// Backup current .env
if (file_exists($envFile)) {
    copy($envFile, $backupFile);
    echo "✅ Backed up current .env to: $backupFile\n";
}

// Read current .env content
$envContent = '';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
}

// Remove all existing Firebase variables
$lines = explode("\n", $envContent);
$cleanLines = [];
foreach ($lines as $line) {
    // Skip lines that start with FIREBASE_
    if (!str_starts_with(trim($line), 'FIREBASE_')) {
        $cleanLines[] = $line;
    }
}

$envContent = implode("\n", $cleanLines);

// Add YOUR Firebase credentials at the end
$envContent .= "\n# Firebase Authentication - Your Credentials\n";
$envContent .= "FIREBASE_API_KEY=AIzaSyBa1_z-kgywejMSOn_aCPNqr_fpuWt9Ukw\n";
$envContent .= "FIREBASE_PROJECT_ID=studentmanagementsystem-74f48\n";
$envContent .= "FIREBASE_AUTH_DOMAIN=studentmanagementsystem-74f48.firebaseapp.com\n";
$envContent .= "FIREBASE_DATABASE_URL=https://studentmanagementsystem-74f48-default-rtdb.firebaseio.com\n";
$envContent .= "FIREBASE_STORAGE_BUCKET=studentmanagementsystem-74f48.firebasestorage.app\n";
$envContent .= "FIREBASE_MESSAGING_SENDER_ID=986803646077\n";
$envContent .= "FIREBASE_APP_ID=1:986803646077:web:3a493bdcc8d418e0914b14\n";
$envContent .= "FIREBASE_MEASUREMENT_ID=G-64F42VE501\n";

// Write back to .env
file_put_contents($envFile, $envContent);

echo "✅ Removed all test Firebase variables\n";
echo "✅ Added your actual Firebase credentials\n";
echo "✅ Updated .env file\n\n";

echo "🔄 Next steps:\n";
echo "1. Run: php artisan cache:clear\n";
echo "2. Go to: http://localhost:8080/login\n";
echo "3. Login: unnikiranj@gmail.com / 12345678\n";
echo "4. Should work! 🎉\n";

<?php

echo "=== FIXING FIREBASE AUTHENTICATION ===\n\n";

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

// Add FIREBASE_PROJECT_ID if not exists
if (!str_contains($envContent, 'FIREBASE_PROJECT_ID=')) {
    $envContent .= "\n# Firebase Authentication\nFIREBASE_PROJECT_ID=your-project-id\n";
    echo "✅ Added FIREBASE_PROJECT_ID\n";
} else {
    echo "ℹ️  FIREBASE_PROJECT_ID already exists\n";
}

// Write back to .env
file_put_contents($envFile, $envContent);

echo "\n🔄 Next steps:\n";
echo "1. Run: php artisan config:cache\n";
echo "2. Try logging in again on Android app\n";
echo "3. Check Laravel logs if still failing\n\n";

echo "✅ Firebase authentication fix complete!\n";

?>

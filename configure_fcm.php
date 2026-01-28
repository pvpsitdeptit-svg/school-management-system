<?php

// FCM Configuration Script
echo "=== CONFIGURING FCM SERVER KEY ===\n\n";

$envFile = '.env';
$serverKey = 'BBUV7cLV6p_znKJKeyRvopWotAZ2zHP3zvFt1Wb7QoLFQQPX2EedrBzAcgi_qfFSi_xAAS_q8VVP1sagLvgW1x0';

// Read current .env content
if (file_exists($envFile)) {
    $content = file_get_contents($envFile);
    
    // Remove existing FCM_SERVER_KEY line if exists
    $content = preg_replace('/^FCM_SERVER_KEY=.*$/m', '', $content);
    
    // Add new FCM_SERVER_KEY at the end
    $content = trim($content) . "\nFCM_SERVER_KEY=" . $serverKey . "\n";
    
    // Write back to .env
    file_put_contents($envFile, $content);
    
    echo "✅ FCM Server Key added to .env file\n";
    echo "   Key: " . substr($serverKey, 0, 20) . "...\n\n";
} else {
    echo "❌ .env file not found\n";
    echo "   Please create .env file and add:\n";
    echo "   FCM_SERVER_KEY=" . $serverKey . "\n\n";
}

echo "=== CONFIGURATION COMPLETE ===\n";
echo "Next steps:\n";
echo "1. Clear Laravel cache: php artisan config:clear\n";
echo "2. Test notifications: php check_notifications.php\n";
echo "3. Clear app data on device and login again\n";

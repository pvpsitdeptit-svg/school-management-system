<?php

// WARNING: This file previously contained hardcoded Firebase credentials.
// Credentials have been removed for security.
// Please use environment variables from .env file instead.

echo "=== FCM SERVICE ACCOUNT SETUP ===\n\n";
echo "=== UPDATING .env FILE ===\n\n";

echo "⚠️  SECURITY NOTICE:\n";
echo "   This script no longer contains hardcoded credentials.\n";
echo "   Please manually add these to your .env file:\n\n";
echo "   FCM_PROJECT_ID=your-project-id\n";
echo "   FCM_PRIVATE_KEY=\"your-private-key\"\n";
echo "   FCM_CLIENT_EMAIL=your-client-email\n\n";
echo "🔧 Steps:\n";
echo "1. Edit your .env file directly\n";
echo "2. Add the Firebase credentials\n";
echo "3. Run: php artisan config:cache\n";
echo "4. Never commit credentials to git!\n";

?>

<?php

echo "=== UPDATING .env FILE ===\n\n";

$envFile = '.env';
$backupFile = '.env.backup.' . date('Y-m-d_H-i-s');

// Backup current .env
if (file_exists($envFile)) {
    copy($envFile, $backupFile);
    echo "✅ Backed up current .env to: $backupFile\n";
}

// FCM Service Account configuration
$fcmConfig = "\n# FCM Service Account Configuration\nFCM_PROJECT_ID=studentmanagementsystem-74f48\nFCM_PRIVATE_KEY=\"-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC1TnDcemusErjV\n0nbFchcifLvzHIdtWaESXPPdHP7fUsxjqAhFpP/fPlqIN7cc0JU9fonDeNC0M+Rf\ngPT/B5ObW713sWjlqiwk+bdcaGalou8bV0iKw4BUG3N+xsr1qTWDTBBWO8la2xUx\nvxElaFGpeZ3JaGzdLteNyt6+kFY3TXti8BQB62+t099FCYpe8FgFDESZSIXNGv8u\nHXmBISsPt9OQiStweaCx51vRZCRu+UKrWMljbVhVN90K7IopEGxpQngmV9laSKtl\nGC6punaeToX2x9P6jFKUIfEEpCHjOqcJLXhqjFViLoDK47G6OMWpChhWRglO3W0s\nNdvTd003AgMBAAECggEACoQJDFLx+YIln3gcZtvim5/AdK3i7PcDlS9ctZQkYfi2\n3Ye2BWZcjE9c9XLF0vqYZDZnOWzmLfs5GEfSgDg7tS3xkZjNMU4PvNVIJnGnuVOu\nhg5MO2S7c5Ma4hQX7OxF0JVtZknfJizBGV9CfQs7Vd/se3/HmcySSuA3GNsBt27C\nirxQ9+bhZXlhAIoZqsz/02f38b0LyccrM7gnQq6OG5l3svPOkPwkREuBEw/4z/qt\nyfPiyJkwK9MJ3V5+iFuc4f2UsthmHgT6c3nN2YoEk7Y2NwrVd3Dz3gLvYPDQWe6A\nzDqbtynyUQ+BJEcA55XYLLzb2rHU9YQywskMF6t2AQKBgQDouQo9cAloH8HKOuj5\n2dKHgpujXi1mcL3O7k1WPSQoHQtEptPwC7Slb6nq+vk6BtKYVii+eN2hZNyCmCL8\nAnpil+oen079PVMXjFrAxcaYZmoHg7LQf5b/jyY2TnO9PX/MuONfnxFW1LZ/b4U+\nYLOkyQzc13RluMrfk9x5kQ1oEQKBgQDHcN0LjG2kM8Csne4nqmHozfXyKiT8uhFE\n6dfdatncPb9sq/krrVK2kpyFaG9ZjcjpwF7AfiboUtC7tc2uBDCznpw/e69Hl4RS\n7lALJQAn9DPfnacHVFlke/tKE08YCCyBsR5OF5tMeVWHVjPEuW5clUw9cx8X7kdC\nKBowy/zoxwKBgA3/g1QqtwYiUt+9J2JP7c/k1UPAWG6+/UvDHbfAObqH9LOObVUU\n/yqsSsYtjmwlGOm81PqP/pTEolbgm8oYxA/GH3j0ECY/WA4kjRjnTIjCMEJ/c3QD\nGKwKijveACwz1MoVnOeVg897P/buWE9mNagFx9ZIx2GO+fT0yeAuD2ZhAoGAIi8d\nJBqYvFlGgA+9vzy79Ww/6JcCqfK2fQpbvo7O8qn+woCjf7IAiFyOyWsCEuZzdmVL\nmFDHGHEBZ/scWuRK+K6J93Oasnms643/WDmqUi7QKVbbRy1U+R0rECJJH0RfZIdY\nO2hebPgrCbxjuSdTXDkWzT2LKc3kltlKy+xa2AcCgYAJNOTRuUvLQCHs29LJpWLd\nCczg2TwDrHKgaIeZ7qFiCEupUypMvqUq4uUSD2d1tcGl9tcSOzDchMN4JfdQCr9P\n37QgNLJzdeTViz71H7PCDZjDdJ3u+VObB2MHf8cZ+2kmyq88hWJWCZWyPRCrfgmW\n//6eZePndNbrtSKOccZa9w==\n-----END PRIVATE KEY-----\"\nFCM_CLIENT_EMAIL=firebase-adminsdk-fbsvc@studentmanagementsystem-74f48.iam.gserviceaccount.com\n";

// Read current .env content
$envContent = '';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
}

// Remove existing FCM configurations
$envContent = preg_replace('/^FCM_.*$/m', '', $envContent);
$envContent = preg_replace('/^# FCM.*$/m', '', $envContent);
$envContent = trim($envContent);

// Add new FCM configuration
$envContent .= $fcmConfig;

// Write back to .env
file_put_contents($envFile, $envContent);

echo "✅ Updated .env file with FCM Service Account configuration\n";
echo "📝 Added:\n";
echo "   - FCM_PROJECT_ID\n";
echo "   - FCM_PRIVATE_KEY\n";
echo "   - FCM_CLIENT_EMAIL\n\n";

echo "🔄 Next steps:\n";
echo "1. Run: php artisan config:cache\n";
echo "2. Test with: php check_notifications.php\n\n";

echo "✅ FCM Service Account setup complete!\n";

?>

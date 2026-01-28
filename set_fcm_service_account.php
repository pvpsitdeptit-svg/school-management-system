<?php

echo "=== FCM SERVICE ACCOUNT SETUP ===\n\n";

// Service Account JSON from Firebase
$serviceAccount = [
    "type" => "service_account",
    "project_id" => "studentmanagementsystem-74f48",
    "private_key_id" => "2848f5b2a3371305fbc839379652dfb780e66487",
    "private_key" => "-----BEGIN PRIVATE KEY-----\nMIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC1TnDcemusErjV\n0nbFchcifLvzHIdtWaESXPPdHP7fUsxjqAhFpP/fPlqIN7cc0JU9fonDeNC0M+Rf\ngPT/B5ObW713sWjlqiwk+bdcaGalou8bV0iKw4BUG3N+xsr1qTWDTBBWO8la2xUx\nvxElaFGpeZ3JaGzdLteNyt6+kFY3TXti8BQB62+t099FCYpe8FgFDESZSIXNGv8u\nHXmBISsPt9OQiStweaCx51vRZCRu+UKrWMljbVhVN90K7IopEGxpQngmV9laSKtl\nGC6punaeToX2x9P6jFKUIfEEpCHjOqcJLXhqjFViLoDK47G6OMWpChhWRglO3W0s\nNdvTd003AgMBAAECggEACoQJDFLx+YIln3gcZtvim5/AdK3i7PcDlS9ctZQkYfi2\n3Ye2BWZcjE9c9XLF0vqYZDZnOWzmLfs5GEfSgDg7tS3xkZjNMU4PvNVIJnGnuVOu\nhg5MO2S7c5Ma4hQX7OxF0JVtZknfJizBGV9CfQs7Vd/se3/HmcySSuA3GNsBt27C\nirxQ9+bhZXlhAIoZqsz/02f38b0LyccrM7gnQq6OG5l3svPOkPwkREuBEw/4z/qt\nyfPiyJkwK9MJ3V5+iFuc4f2UsthmHgT6c3nN2YoEk7Y2NwrVd3Dz3gLvYPDQWe6A\nzDqbtynyUQ+BJEcA55XYLLzb2rHU9YQywskMF6t2AQKBgQDouQo9cAloH8HKOuj5\n2dKHgpujXi1mcL3O7k1WPSQoHQtEptPwC7Slb6nq+vk6BtKYVii+eN2hZNyCmCL8\nAnpil+oen079PVMXjFrAxcaYZmoHg7LQf5b/jyY2TnO9PX/MuONfnxFW1LZ/b4U+\nYLOkyQzc13RluMrfk9x5kQ1oEQKBgQDHcN0LjG2kM8Csne4nqmHozfXyKiT8uhFE\n6dfdatncPb9sq/krrVK2kpyFaG9ZjcjpwF7AfiboUtC7tc2uBDCznpw/e69Hl4RS\n7lALJQAn9DPfnacHVFlke/tKE08YCCyBsR5OF5tMeVWHVjPEuW5clUw9cx8X7kdC\nKBowy/zoxwKBgA3/g1QqtwYiUt+9J2JP7c/k1UPAWG6+/UvDHbfAObqH9LOObVUU\n/yqsSsYtjmwlGOm81PqP/pTEolbgm8oYxA/GH3j0ECY/WA4kjRjnTIjCMEJ/c3QD\nGKwKijveACwz1MoVnOeVg897P/buWE9mNagFx9ZIx2GO+fT0yeAuD2ZhAoGAIi8d\nJBqYvFlGgA+9vzy79Ww/6JcCqfK2fQpbvo7O8qn+woCjf7IAiFyOyWsCEuZzdmVL\nmFDHGHEBZ/scWuRK+K6J93Oasnms643/WDmqUi7QKVbbRy1U+R0rECJJH0RfZIdY\nO2hebPgrCbxjuSdTXDkWzT2LKc3kltlKy+xa2AcCgYAJNOTRuUvLQCHs29LJpWLd\nCczg2TwDrHKgaIeZ7qFiCEupUypMvqUq4uUSD2d1tcGl9tcSOzDchMN4JfdQCr9P\n37QgNLJzdeTViz71H7PCDZjDdJ3u+VObB2MHf8cZ+2kmyq88hWJWCZWyPRCrfgmW\n//6eZePndNbrtSKOccZa9w==\n-----END PRIVATE KEY-----\n",
    "client_email" => "firebase-adminsdk-fbsvc@studentmanagementsystem-74f48.iam.gserviceaccount.com",
    "client_id" => "111944950438552843631",
    "auth_uri" => "https://accounts.google.com/o/oauth2/auth",
    "token_uri" => "https://oauth2.googleapis.com/token",
    "auth_provider_x509_cert_url" => "https://www.googleapis.com/oauth2/v1/certs",
    "client_x509_cert_url" => "https://www.googleapis.com/robot/v1/metadata/x509/firebase-adminsdk-fbsvc%40studentmanagementsystem-74f48.iam.gserviceaccount.com",
    "universe_domain" => "googleapis.com"
];

// Create .env entries
$envContent = "
# FCM Service Account Configuration
FCM_PROJECT_ID={$serviceAccount['project_id']}
FCM_PRIVATE_KEY=\"{$serviceAccount['private_key']}\"
FCM_CLIENT_EMAIL={$serviceAccount['client_email']}
";

echo "✅ Service Account Details:\n";
echo "   - Project ID: {$serviceAccount['project_id']}\n";
echo "   - Client Email: {$serviceAccount['client_email']}\n";
echo "   - Private Key ID: {$serviceAccount['private_key_id']}\n\n";

echo "📝 Add these to your .env file:\n";
echo $envContent . "\n";

echo "🔧 Next Steps:\n";
echo "1. Add the above lines to your .env file\n";
echo "2. Run: php artisan config:cache\n";
echo "3. Test with: php check_notifications.php\n\n";

echo "✅ Service Account is more secure and recommended by Google!\n";

?>

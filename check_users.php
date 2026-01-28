<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$kernel->bootstrap();

// Get all users
$users = App\Models\User::all(['id', 'name', 'email', 'role', 'firebase_uid']);

echo "=== USERS IN DATABASE ===\n";
foreach ($users as $user) {
    echo "ID: {$user->id}\n";
    echo "Name: {$user->name}\n";
    echo "Email: {$user->email}\n";
    echo "Role: {$user->role}\n";
    echo "Firebase UID: " . ($user->firebase_uid ?? 'NULL') . "\n";
    echo "------------------------\n";
}

echo "\nTotal users: " . $users->count() . "\n";

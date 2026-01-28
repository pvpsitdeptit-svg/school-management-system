<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING USERS TABLE STRUCTURE ===\n";

try {
    // Get the table schema
    $schema = DB::select("PRAGMA table_info(users)");
    
    echo "Users table columns:\n";
    foreach ($schema as $column) {
        $nullable = $column->notnull ? 'NOT NULL' : 'NULL';
        $default = $column->dflt_value ? "DEFAULT {$column->dflt_value}" : '';
        echo "- {$column->name} ({$column->type}) {$nullable} {$default}\n";
    }
    
    echo "\n=== CHECKING RECENT USERS ===\n";
    
    $recentUsers = DB::table('users')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($recentUsers as $user) {
        echo "ID: {$user->id}, Email: {$user->email}, Role: {$user->role}, Firebase UID: " . ($user->firebase_uid ?? 'NULL') . "\n";
    }
    
    echo "\n=== TESTING USER CREATION ===\n";
    
    // Test creating a user manually
    $testData = [
        'name' => 'Test Admin ' . time(),
        'email' => 'test' . time() . '@example.com',
        'password' => null,
        'role' => 'school_admin',
        'school_id' => 1,
        'firebase_uid' => 'test_uid_' . time(),
        'status' => 'active',
    ];
    
    echo "Creating test user with data:\n";
    foreach ($testData as $key => $value) {
        echo "- {$key}: " . ($value ?? 'NULL') . "\n";
    }
    
    try {
        $userId = DB::table('users')->insertGetId($testData);
        echo "✅ Test user created with ID: {$userId}\n";
        
        // Clean up
        DB::table('users')->where('id', $userId)->delete();
        echo "✅ Test user cleaned up\n";
        
    } catch (Exception $e) {
        echo "❌ Test user creation failed: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error checking database: " . $e->getMessage() . "\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Try creating a school and check the logs\n";
echo "2. Look for detailed error messages in storage/logs/laravel.log\n";
echo "3. Check if all required fields are being provided\n";
echo "4. Verify database constraints are not blocking user creation\n";

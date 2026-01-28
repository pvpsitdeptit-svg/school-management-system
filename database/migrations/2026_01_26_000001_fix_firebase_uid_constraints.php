<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, make password column nullable
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
        });
        
        // Fix existing school admins without Firebase UIDs
        $schoolAdmins = DB::table('users')
            ->where('role', 'school_admin')
            ->whereNull('firebase_uid')
            ->get();

        foreach ($schoolAdmins as $admin) {
            Log::warning("School admin without Firebase UID found: ID {$admin->id}, Email {$admin->email}");
            
            // For now, we'll add a placeholder to prevent login
            // In production, these should be properly created in Firebase
            DB::table('users')
                ->where('id', $admin->id)
                ->update([
                    'firebase_uid' => 'legacy_' . $admin->id . '_needs_firebase_setup',
                    'password' => null, // Remove password hash
                ]);
        }

        // Remove password hashes from Firebase users
        DB::table('users')
            ->where('role', '!=', 'super_admin')
            ->whereNotNull('password')
            ->update(['password' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not easily reversible
        // In production, you should have proper backup and rollback strategy
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('firebase_uid')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'faculty', 'student', 'parent'])->default('student');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->index(['school_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropIndex(['school_id', 'email']);
            $table->dropColumn(['school_id', 'firebase_uid', 'role', 'status']);
        });
    }
};

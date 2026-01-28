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
        Schema::create('device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('fcm_token');
            $table->string('device_type')->default('android');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
            
            // Indexes for performance
            $table->unique(['fcm_token', 'device_type']);
            $table->index(['user_id', 'device_type']);
            $table->index(['student_id', 'device_type']);
            $table->index(['school_id', 'device_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_tokens');
    }
};

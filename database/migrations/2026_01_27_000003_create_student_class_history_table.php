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
        Schema::create('student_class_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('academic_year'); // e.g., "2024-2025"
            $table->date('from_date');
            $table->date('to_date')->nullable();
            $table->enum('status', ['active', 'completed', 'transferred'])->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Performance indexes
            $table->index(['school_id', 'student_id']);
            $table->index(['school_id', 'class_id']);
            $table->index(['student_id', 'academic_year']);
            $table->index(['academic_year', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_class_history');
    }
};

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
        Schema::table('subjects', function (Blueprint $table) {
            // Update index to remove class_id first
            $table->dropIndex(['school_id', 'class_id']);
            $table->index(['school_id', 'name']);
            
            // Drop foreign key and class_id column
            $table->dropForeign(['class_id']);
            $table->dropColumn('class_id');
            
            // Add code column
            $table->string('code')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Add back class_id column
            $table->foreignId('class_id')->constrained()->onDelete('cascade')->after('school_id');
            
            // Drop code column
            $table->dropColumn('code');
            
            // Update index to include class_id
            $table->dropIndex(['school_id', 'name']);
            $table->index(['school_id', 'class_id']);
        });
    }
};

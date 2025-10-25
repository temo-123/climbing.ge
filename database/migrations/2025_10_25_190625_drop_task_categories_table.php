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
        // Drop the foreign key from tasks table first
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        // Then drop the task_categories table
        Schema::dropIfExists('task_categories');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the task_categories table
        Schema::create('task_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('short_description')->nullable();
            $table->timestamps();
        });

        // Add back the foreign key to tasks table
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('task_categories');
        });
    }
};

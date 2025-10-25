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
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the foreign key and column for category_id
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');

            // Drop the foreign key and column for user_id
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Add new columns
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('for_user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('global_category')->nullable();
            $table->string('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // Drop the new columns
            $table->dropForeign(['from_user_id']);
            $table->dropColumn('from_user_id');

            $table->dropForeign(['for_user_id']);
            $table->dropColumn('for_user_id');

            $table->dropColumn('global_category');
            $table->dropColumn('category');

            // Add back the old columns
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('task_categories');
        });
    }
};

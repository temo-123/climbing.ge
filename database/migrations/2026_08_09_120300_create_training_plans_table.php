<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_plans', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('name', 120);
            $table->string('emoji', 16)->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'expert', 'maintenance']);
            $table->string('tagline', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('coach_note')->nullable();
            $table->integer('days_per_week');
            $table->integer('weeks')->default(0);
            $table->boolean('is_published')->default(1);
            $table->timestamps();

            $table->index('is_published', 'idx_plans_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_plans');
    }
};

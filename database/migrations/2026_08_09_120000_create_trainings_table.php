<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainings', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->enum('type', ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance']);
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->string('target_muscle', 160)->nullable();
            $table->text('coach_tip')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->integer('hang_time')->default(7);
            $table->integer('rest_time')->default(3);
            $table->integer('reps')->default(6);
            $table->integer('sets')->default(4);
            $table->integer('recover_time')->default(180);
            $table->boolean('is_published')->default(1);
            $table->timestamps();

            $table->index('type', 'idx_trainings_type');
            $table->index('is_published', 'idx_trainings_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainings');
    }
};

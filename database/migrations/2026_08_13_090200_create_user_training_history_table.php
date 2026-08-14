<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Training mobile app sync: completed workout session history.
     * `date` is the app's completion timestamp and is the row's identity
     * client-side (there is no separate id) -- must be echoed back unchanged.
     */
    public function up(): void
    {
        Schema::create('user_training_history', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('date', 32);

            $table->string('workout_name', 160);
            $table->enum('workout_type', ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance'])->nullable();
            $table->integer('reps_completed')->default(0);
            $table->integer('sets_completed')->default(0);
            $table->enum('status', ['success', 'failed']);
            $table->string('plan_id', 64)->nullable();
            $table->string('updated_at', 32)->nullable();

            $table->unique(['user_id', 'date'], 'uniq_user_training_history');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_training_history');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Training mobile app sync: a user's custom (self-created) workouts.
     * `client_id` is the app's client-generated id (Date.now().toString()
     * today) and must be echoed back unchanged in the API response -- the
     * app merges local/remote state by matching on it.
     */
    public function up(): void
    {
        Schema::create('user_workouts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('client_id', 64);

            $table->string('name', 120);
            $table->text('description')->nullable();
            $table->enum('type', ['fingerboard', 'campus', 'flexibility', 'strength', 'endurance']);
            $table->integer('hang_time')->default(0);
            $table->integer('rest_time')->default(0);
            $table->integer('reps')->default(0);
            $table->integer('sets')->default(0);
            $table->integer('recover_time')->default(0);

            // Plain opaque strings (app-supplied ISO timestamps), not Laravel
            // timestamp casts -- compared as raw strings by the app's
            // conflict resolution, so must be stored/echoed byte-for-byte.
            $table->string('updated_at', 32)->nullable();
            $table->string('deleted_at', 32)->nullable();

            $table->unique(['user_id', 'client_id'], 'uniq_user_workout');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_workouts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('training_id', 64);
            $table->integer('step_order');
            $table->enum('phase', ['prepare', 'hang', 'rest', 'recover', 'work', 'stretch']);
            $table->string('label', 120)->nullable();
            $table->integer('duration_seconds');
            $table->string('image_url', 500)->nullable();
            $table->text('instructions')->nullable();

            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->unique(['training_id', 'step_order'], 'uniq_training_step_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_steps');
    }
};

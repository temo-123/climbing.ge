<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('training_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('training_id', 64);
            $table->string('locale', 8);
            $table->string('name', 120)->nullable();
            $table->text('description')->nullable();
            $table->text('coach_tip')->nullable();
            $table->string('target_muscle', 160)->nullable();

            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('cascade');
            $table->unique(['training_id', 'locale'], 'uniq_training_locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_translations');
    }
};

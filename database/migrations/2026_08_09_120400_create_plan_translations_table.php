<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_translations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan_id', 64);
            $table->string('locale', 8);
            $table->string('name', 120)->nullable();
            $table->string('tagline', 200)->nullable();
            $table->text('description')->nullable();
            $table->text('coach_note')->nullable();

            $table->foreign('plan_id')->references('id')->on('training_plans')->onDelete('cascade');
            $table->unique(['plan_id', 'locale'], 'uniq_plan_locale');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_translations');
    }
};

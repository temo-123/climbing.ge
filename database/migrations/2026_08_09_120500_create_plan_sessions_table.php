<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plan_id', 64);
            $table->tinyInteger('day_index');
            $table->string('day_label', 40);

            $table->foreign('plan_id')->references('id')->on('training_plans')->onDelete('cascade');
            $table->unique(['plan_id', 'day_index'], 'uniq_plan_day');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_sessions');
    }
};

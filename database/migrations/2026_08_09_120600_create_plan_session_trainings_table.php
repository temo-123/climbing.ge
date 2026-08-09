<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_session_trainings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('plan_session_id');
            $table->string('training_id', 64);
            $table->integer('sort_order')->default(0);

            $table->foreign('plan_session_id')->references('id')->on('plan_sessions')->onDelete('cascade');
            $table->foreign('training_id')->references('id')->on('trainings')->onDelete('restrict');
            $table->index(['plan_session_id', 'sort_order'], 'idx_pst_session');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_session_trainings');
    }
};

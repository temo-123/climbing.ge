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
        Schema::dropIfExists('product_feedback_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('product_feedback_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            
            $table->unsignedBigInteger('feedback_id');
            $table->foreign('feedback_id')->references('id')->on('product_feedbacks');

            $table->timestamps();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_feedback_complaints', function (Blueprint $table) {
            $table->id();

            $table->string('complaint')->nullable();
            $table->string('cause')->nullable();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('feedback_id');
            $table->foreign('feedback_id')->references('id')->on('product_feedbacks')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_feedback_complaints');
    }
};

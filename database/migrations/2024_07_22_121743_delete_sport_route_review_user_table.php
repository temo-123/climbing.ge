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
        Schema::dropIfExists('sport_route_review_user');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('sport_route_review_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained();
            
            $table->unsignedBigInteger('review_id');
            $table->foreign('review_id')->references('id')->on('sport_route_reviews');

            $table->timestamps();
        });
    }
};

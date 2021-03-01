<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStarReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('star_reviews', function (Blueprint $table) {
            $table->id();

            $table->string('category')->nullable();
            $table->string('article_review')->nullable();

            $table->integer('review_value')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('sector_id')->nullable();
            
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
        Schema::dropIfExists('star_reviews');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('url_title')->nullable();

            $table->integer('published')->nullable();
            $table->integer('completed')->nullable();

            $table->string('start_data_day')->nullable();
            $table->string('and_data_day')->nullable();
            $table->string('start_data_month')->nullable();
            $table->string('and_data_month')->nullable();

            $table->string('fb_link')->nullable();
            $table->string('inst_link')->nullable();
            $table->string('google_link')->nullable();
            $table->string('twit_link')->nullable();
            $table->string('web_link')->nullable();

            $table->string('star')->nullable();

            $table->string('price_from')->nullable();
            $table->string('working_time')->nullable();

            $table->text('weather')->nullable();
            $table->text('map')->nullable();
            $table->string('image')->nullable();
            $table->string('climbing_area_image')->nullable();

            $table->integer('mount_id')->nullable();

            $table->integer('us_article_id')->nullable();
            $table->integer('ru_article_id')->nullable();
            $table->integer('ka_article_id')->nullable();

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
        Schema::dropIfExists('articles');
    }
}

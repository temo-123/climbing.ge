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
            $table->integer('category');
            $table->string('url_title');

            $table->integer('published');
            $table->integer('completed');

            $table->string('start_data_day');
            $table->astring('nd_data_day');
            $table->string('start_data_month');
            $table->string('and_data_month');

            $table->string('fb_link');
            $table->string('inst_link');
            $table->string('google_link');
            $table->string('twit_link');
            $table->string('web_link');

            $table->string('star');

            $table->string('price_from');
            $table->string('working_time');

            $table->text('weather');
            $table->text('map');

            $table->integer('mount_id');

            $table->integer('us_article_id');
            $table->integer('ru_article_id');
            $table->integer('ka_article_id');

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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeteArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('category')->nullable();

            $table->string('title')->nullable();
            $table->string('url_title')->nullable();
            
            $table->text('text')->nullable();
            $table->string('short_description', 225)->nullable();

            $table->text('route')->nullable();
            $table->text('how_get')->nullable();
            $table->text('best_time')->nullable();
            $table->text('weather')->nullable();
            $table->text('what_need')->nullable();
            $table->text('info')->nullable();

            $table->string('time')->nullable();
            $table->text('address')->nullable();

            $table->text('prices_text')->nullable();
            $table->string('prices_from')->nullable();

            $table->integer('start_data_day')->nullable();
            $table->string('start_data_month', 50)->nullable();
            $table->integer('and_data_day')->nullable();
            $table->string('and_data_month', 50)->nullable();

            $table->text('fb_link')->nullable();
            $table->text('inst_link')->nullable();
            $table->text('google_link')->nullable();
            $table->text('twit_link')->nullable();
            $table->text('web_link')->nullable();

            $table->text('map')->nullable();

            $table->integer('star')->nullable();

            $table->string('meta_title');
            $table->string('meta_description');
            $table->string('meta_keyword');

            $table->text('image')->nullable();

            $table->integer('completed')->nullable();
            $table->integer('published')->nullable();
            
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

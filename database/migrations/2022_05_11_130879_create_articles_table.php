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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('category')->nullable();
            $table->string('url_title')->unique();

            // $table->string('fb_link')->nullable();
            // $table->string('inst_link')->nullable();
            // $table->string('google_link')->nullable();
            // $table->string('twit_link')->nullable();
            // $table->string('web_link')->nullable();

            // $table->string('star')->nullable();

            $table->string('price_from')->nullable();

            $table->timestamp('open_time')->nullable();
            $table->timestamp('closed_time')->nullable();

            $table->text('weather')->nullable();
            $table->text('map')->nullable();
            $table->string('image')->nullable();

            $table->integer('published')->nullable();
            
            // ->constrained() for onDelete working. Wizout this it not working
            $table->unsignedBigInteger('us_article_id');
            $table->foreign('us_article_id')->references('id')->on('locale_articles')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ka_article_id');
            $table->foreign('ka_article_id')->references('id')->on('locale_articles')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ru_article_id');
            $table->foreign('ru_article_id')->references('id')->on('locale_articles')->constrained()->onDelete('cascade');

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
};

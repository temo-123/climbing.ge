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
            $table->id();
            
            $table->string('category')->nullable();
            $table->string('url_title')->unique();

            $table->timestamp('start_data')->nullable();
            $table->timestamp('end_data')->nullable();

            $table->string('fb_link')->nullable();
            $table->string('inst_link')->nullable();
            $table->string('google_link')->nullable();
            $table->string('twit_link')->nullable();
            $table->string('web_link')->nullable();

            $table->string('star')->nullable();

            $table->string('price_from')->nullable();

            $table->string('open_timen')->nullable();
            $table->string('closed_time')->nullable();

            $table->text('weather')->nullable();
            $table->text('map')->nullable();
            $table->string('image')->nullable();

            // $table->string('region_id')->nullable();

            // $table->integer('mount_id')->nullable();
            // $table->foreignId('mount_id')->constrained();
            // $table->integer('region_id')->nullable();
            // $table->foreignId('region_id')->constrained();

            // $table->integer('us_article_id')->nullable();
            // $table->integer('ru_article_id')->nullable();
            // $table->integer('ka_article_id')->nullable();

            // $table->usignedBigInteger('us_article_id');
            // $table->usignedBigInteger('ru_article_id');
            // $table->usignedBigInteger('ka_article_id');

            // $table->foreignId('us_article_id')->references()->on('id')->onDelete('cascade');
            // $table->foreignId('ru_article_id')->references()->on('id')->onDelete('cascade');
            // $table->foreignId('ka_article_id')->references()->on('id')->onDelete('cascade');

            // $table->foreignId('locale_article_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_article_id')->constrained()->onDelete('cascade');
            // $table->foreignId('locale_article_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('us_article_id');
            $table->foreign('us_article_id')->references('id')->on('locale_articles')->onDelete('cascade');

            $table->unsignedBigInteger('ka_article_id');
            $table->foreign('ka_article_id')->references('id')->on('locale_articles')->onDelete('cascade');

            $table->unsignedBigInteger('ru_article_id');
            $table->foreign('ru_article_id')->references('id')->on('locale_articles')->onDelete('cascade');

            $table->integer('published')->nullable();
            $table->integer('completed')->nullable();

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

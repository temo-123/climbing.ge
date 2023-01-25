<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('films', function (Blueprint $table) {
            
            $table->bigIncrements('id');

            $table->string('url_title')->nullable();

            $table->unsignedBigInteger('us_film_id');
            $table->foreign('us_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ka_film_id');
            $table->foreign('ka_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ru_film_id');
            $table->foreign('ru_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

            $table->integer('published')->nullable();
            $table->string('image')->nullable();

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
        Schema::dropIfExists('films');
    }
}

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
            $table->id();

            $table->integer('published')->nullable();
            $table->string('image')->nullable();

            $table->increments('us_film_id');
            // $table->unsignedBigInteger('us_film_id');
            $table->foreign('us_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

            $table->increments('ka_film_id');
            // $table->unsignedBigInteger('ka_film_id');
            $table->foreign('ka_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

            $table->increments('ru_film_id');
            // $table->unsignedBigInteger('ru_film_id');
            $table->foreign('ru_film_id')->references('id')->on('locale_films')->constrained()->onDelete('cascade');

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

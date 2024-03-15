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
        Schema::create('tours', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('published')->nullable();

            $table->string('url_title')->nullable();
            $table->string('min_price')->nullable();
            $table->string('location')->nullable();
            $table->string('duration')->nullable();
            // $table->string('image')->nullable();

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('tour_categories')->constrained()->onDelete('cascade');
            
            // ->constrained() for onDelete working. Wizout this it not working
            $table->unsignedBigInteger('us_tour_id');
            $table->foreign('us_tour_id')->references('id')->on('locale_tours')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ka_tour_id');
            $table->foreign('ka_tour_id')->references('id')->on('locale_tours')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ru_tour_id');
            $table->foreign('ru_tour_id')->references('id')->on('locale_tours')->constrained()->onDelete('cascade');
            
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
        Schema::dropIfExists('tours');
    }
};

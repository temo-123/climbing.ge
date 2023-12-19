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
        Schema::create('sport_route_reviews', function (Blueprint $table) {
            $table->id();

            $table->string('stars')->nullable();

            $table->string('text')->nullable();
            $table->string('climbed_data')->nullable();

            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes');
            
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
        Schema::dropIfExists('sport_route_reviews');
    }
};

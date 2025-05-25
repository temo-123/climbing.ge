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
        Schema::create('tour_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title')->nullable();
            
            $table->string('image')->nullable();

            // $table->foreignId('tour_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('tour_id');
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

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
        Schema::dropIfExists('tour_images');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOptionImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('option_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('image')->nullable();
            
            // $table->integer('color_id')->nullable();
            // $table->foreignId('color_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->references('id')->on('product_options')->onDelete('cascade');

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
        Schema::dropIfExists('option_images');
    }
}

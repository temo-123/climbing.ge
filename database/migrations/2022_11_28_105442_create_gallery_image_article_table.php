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
        Schema::create('gallery_image_article', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('image_id');
            $table->foreign('image_id')->references('id')->on('gallery_images')->onDelete('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            // $table->foreignId('tag_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('gallery_image_article');
    }
};

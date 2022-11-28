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
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();

            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->integer('published')->nullable();
            $table->string('image_type')->nullable();
            $table->string('link')->nullable();

            // $table->integer('category_id')->nullable();
            // $table->foreignId('category_id')->constrained()
            // $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('gallery_categories')->onDelete('cascade');

            // $table->integer('article_id')->nullable();
            // $table->foreignId('article_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('galleries');
    }
};

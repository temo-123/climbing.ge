<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->integer('published')->nullable();
            $table->integer('category')->nullable();
            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->string('image')->nullable();
            $table->string('filter')->nullable();
            $table->integer('index_gallery_image')->nullable();
            $table->integer('index_header_image')->nullable();
            $table->integer('article_id')->nullable();
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
}

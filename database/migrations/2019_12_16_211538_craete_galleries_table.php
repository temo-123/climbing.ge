<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CraeteGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('category')->nullable();

            $table->string('title')->nullable();
            $table->text('text')->nullable();
            $table->text('link')->nullable();
            $table->text('image')->nullable();
            $table->string('filter')->nullable();
            $table->integer('published')->nullable();

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

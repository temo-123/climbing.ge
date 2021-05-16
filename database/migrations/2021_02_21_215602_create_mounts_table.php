<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mounts', function (Blueprint $table) {
            $table->id();
            $table->integer('published')->nullable();
            
            $table->string('name')->nullable();
            $table->string('name_ru')->nullable();
            $table->string('name_ka')->nullable();
            
            $table->string('short_description')->nullable();
            $table->string('short_description_ru')->nullable();
            $table->string('short_description_ka')->nullable();
            
            $table->text('text')->nullable();
            $table->text('text_ru')->nullable();
            $table->text('text_ka')->nullable();
            
            $table->text('how_get')->nullable();
            $table->text('how_get_ru')->nullable();
            $table->text('how_get_ka')->nullable();
            
            $table->text('best_time')->nullable();
            $table->text('best_time_ru')->nullable();
            $table->text('best_time_ka')->nullable();

            $table->text('map')->nullable();
            $table->text('weather')->nullable();
            
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
        Schema::dropIfExists('mounts');
    }
}

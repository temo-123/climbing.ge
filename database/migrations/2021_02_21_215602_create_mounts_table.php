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
            $table->integer('published');
            
            $table->string('name');
            $table->string('name_ru');
            $table->string('name_ka');
            
            $table->string('description');
            $table->string('description_ru');
            $table->string('description_ka');
            
            $table->text('text');
            $table->text('text_ru');
            $table->text('text_ka');
            
            $table->text('how_get');
            $table->text('how_get_ru');
            $table->text('how_get_ka');
            
            $table->text('best_time');
            $table->text('best_time_ru');
            $table->text('best_time_ka');

            $table->text('map');
            $table->text('weather');
            
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

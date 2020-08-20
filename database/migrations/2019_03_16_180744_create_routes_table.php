<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('number')->nullable();
            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->integer('height')->nullable();
            $table->integer('bolts')->nullable();
            $table->string('gread')->nullable();
            // $table->string('gread_fr')->nullable();
            // $table->string('gread_yds')->nullable();
            // $table->string('grade_boulder')->nullable();
            $table->integer('last_carabin')->nullable();
            $table->string('first_ascent')->nullable();
            $table->string('bolter')->nullable();
            $table->string('category')->nullable();
            $table->integer('stars')->nullable();
            $table->string('sector')->nullable();
            
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
        Schema::dropIfExists('routes');
    }
}

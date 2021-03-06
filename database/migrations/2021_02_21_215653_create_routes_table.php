<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();

            $table->string('name')->nullable();
            $table->text('text')->nullable();

            $table->string('gread')->nullable();
            $table->string('or_gread')->nullable();

            $table->string('bolts')->nullable();
            $table->string('bolter')->nullable();
            $table->string('bolting_data')->nullable();
            $table->string('height')->nullable();
            $table->string('first_ascent')->nullable();
            $table->string('last_carabin')->nullable();
            $table->string('category')->nullable();
            $table->string('stars')->nullable();

            $table->integer('num')->nullable();
            $table->integer('sector_id')->nullable();

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

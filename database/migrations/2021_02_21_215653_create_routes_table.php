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

            $table->string('name');
            $table->text('text');

            $table->string('gread');
            $table->string('or_gread');

            $table->string('bolts');
            $table->string('bolter');
            $table->string('bolting_data');
            $table->string('height');
            $table->string('first_ascent');
            $table->string('last_carabin');
            $table->string('category');
            $table->string('stars');

            $table->integer('num');
            $table->integer('sector_id');

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

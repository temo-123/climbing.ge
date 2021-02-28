<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sectors', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->text('text');

            $table->string('all_day_in_shade');
            $table->string('all_day_in_sun');
            $table->string('in_the_shade_afternoon');
            $table->string('in_the_shade_befornoon');
            $table->string('in_shade_after_10');
            $table->string('in_shade_after_15');
            
            $table->string('overhang');
            $table->string('slabby');
            $table->string('vertical');

            $table->integer('num');
            $table->integer('article_id');
            
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
        Schema::dropIfExists('sectors');
    }
}

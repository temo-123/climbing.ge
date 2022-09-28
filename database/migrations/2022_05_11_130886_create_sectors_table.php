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

            $table->string('name')->nullable();
            $table->text('text')->nullable();

            $table->string('all_day_in_shade')->nullable();
            $table->string('all_day_in_sun')->nullable();
            $table->string('in_the_shade_afternoon')->nullable();
            $table->string('in_the_shade_befornoon')->nullable();
            $table->string('in_shade_after_10')->nullable();
            $table->string('in_shade_after_15')->nullable();
            
            $table->string('roof')->nullable();
            $table->string('overhang')->nullable();
            $table->string('slabby')->nullable();
            $table->string('vertical')->nullable();

            $table->integer('num')->nullable();
            // $table->integer('article_id')->nullable();

            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            
            $table->integer('published')->nullable();
            
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

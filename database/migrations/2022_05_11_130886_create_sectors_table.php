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

            $table->integer('all_day_in_shade')->nullable();
            $table->integer('all_day_in_sun')->nullable();
            $table->integer('in_the_shade_afternoon')->nullable();
            $table->integer('in_the_shade_befornoon')->nullable();
            $table->integer('in_shade_after_10')->nullable();
            $table->integer('in_shade_after_15')->nullable();

            $table->integer('for_kids')->nullable();
            $table->integer('for_family')->nullable();

            $table->string('wolking_time')->nullable();
            
            $table->integer('roof')->nullable();
            $table->integer('overhang')->nullable();
            $table->integer('slabby')->nullable();
            $table->integer('vertical')->nullable();

            $table->integer('num')->nullable();

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

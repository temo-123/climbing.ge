<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');

            $table->string('name')->nullable();
            $table->string('filter_name')->nullable();

            $table->string('description_short')->nullable();
            $table->text('text')->nullable();
            
            $table->text('map')->nullable();
            
            $table->text('how_get')->nullable();
            $table->text('best_time')->nullable();
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->text('text')->nullable();
            $table->integer('height')->nullable();


            $table->string('author')->nullable();
            $table->string('first_ascent')->nullable();
            
            $table->integer('num')->nullable();
            // $table->integer('sector_id')->nullable();
            
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');

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
        Schema::dropIfExists('mtps');
    }
}

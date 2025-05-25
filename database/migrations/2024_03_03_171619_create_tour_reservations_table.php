<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_reservations', function (Blueprint $table) {
            $table->id();

            $table->string('check_in')->nullable();
            $table->integer('persons')->nullable();
            
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            $table->string('text')->nullable();

            $table->integer('confirm')->nullable();

            $table->unsignedBigInteger('tour_id');
            $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');

            $table->integer('verificate')->nullable();

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
        Schema::dropIfExists('tour_reservations');
    }
};

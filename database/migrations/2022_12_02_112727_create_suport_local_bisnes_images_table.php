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
        Schema::create('suport_local_bisnes_images', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('image')->nullable();

            $table->unsignedBigInteger('bisnes_id');
            $table->foreign('bisnes_id')->references('id')->on('suport_local_bisneses');

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
        Schema::dropIfExists('suport_local_bisnes_images');
    }
};

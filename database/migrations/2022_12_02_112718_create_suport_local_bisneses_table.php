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
        Schema::create('suport_local_bisneses', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('url_title')->unique();

            $table->integer('published')->nullable();

            $table->timestamp('published_data')->nullable();
            $table->integer('public_totaly')->nullable();

            $table->unsignedBigInteger('us_bisnes_id');
            $table->foreign('us_bisnes_id')->references('id')->on('locale_bisneses');

            $table->unsignedBigInteger('ka_bisnes_id');
            $table->foreign('ka_bisnes_id')->references('id')->on('locale_bisneses');

            $table->unsignedBigInteger('ru_bisnes_id');
            $table->foreign('ru_bisnes_id')->references('id')->on('locale_bisneses');

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
        Schema::dropIfExists('suport_local_bisneses');
    }
};

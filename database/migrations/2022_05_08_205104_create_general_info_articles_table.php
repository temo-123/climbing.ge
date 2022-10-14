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
        Schema::create('general_info_articles', function (Blueprint $table) {
            $table->id();

            // $table->integer('info_id')->nullable();
            // $table->integer('article_id')->nullable();

            $table->foreign('info_id')->references('id')->on('general_infos')->onDelete('cascade'); // froom
            $table->foreign('article_id')->references('id')->on('general_infos')->onDelete('cascade'); // froom

            $table->string('block')->nullable();
            $table->string('block_action')->nullable();
            
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
        Schema::dropIfExists('general_info_articles');
    }
};

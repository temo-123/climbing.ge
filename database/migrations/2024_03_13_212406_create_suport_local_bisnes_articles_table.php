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
        Schema::create('suport_local_bisnes_articles', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('bisnes_id');
            $table->foreign('bisnes_id')->references('id')->on('suport_local_bisneses')->onDelete('cascade');

            // $table->foreignId('article_id')->constrained();
            $table->unsignedBigInteger('article_id');
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            
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
        Schema::dropIfExists('suport_local_bisnes_articles');
    }
};

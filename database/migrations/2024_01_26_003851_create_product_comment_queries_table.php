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
        Schema::create('product_comment_queries', function (Blueprint $table) {
            $table->bigIncrements('id');
        
            $table->foreignId('user_id')->constrained();

            $table->unsignedBigInteger('comment_id');
            $table->foreign('comment_id')->references('id')->on('product_comments');

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
        Schema::dropIfExists('product_comment_queries');
    }
};

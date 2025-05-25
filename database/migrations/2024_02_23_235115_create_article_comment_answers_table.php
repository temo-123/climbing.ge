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
        Schema::create('article_comment_answers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('comment_id');
            $table->foreign('comment_id')->references('id')->on('article_comments')->onDelete('cascade');
            
            $table->unsignedBigInteger('answer_id');
            $table->foreign('answer_id')->references('id')->on('article_comments')->onDelete('cascade');

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
        Schema::dropIfExists('article_comment_answers');
    }
};

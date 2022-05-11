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
        Schema::create('post_complaints', function (Blueprint $table) {
            $table->id();

            $table->text('comment')->nullable();
            $table->string('category')->nullable();
            $table->string('decision')->nullable();

            // $table->integer('user_id')->nullable();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // $table->foreignId('post_id')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('forum_posts')->onDelete('cascade');
            
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
        Schema::dropIfExists('post_complaints');
    }
};

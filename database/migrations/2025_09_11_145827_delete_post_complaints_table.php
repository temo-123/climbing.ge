<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('post_complaints');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('post_complaints', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('comment')->nullable();
            $table->string('category')->nullable();
            $table->string('decision')->nullable();

            // $table->integer('user_id')->nullable();
            
            $table->foreignId('user_id')->constrained();
            // $table->foreignId('post_id')->constrained();

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('forum_posts');
            
            $table->timestamps();
        });
    }
};

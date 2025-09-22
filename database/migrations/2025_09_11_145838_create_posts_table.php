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
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('published')->nullable();

            $table->string('url_title')->nullable();

            $table->string('image')->nullable();

            $table->unsignedBigInteger('us_post_id');
            $table->foreign('us_post_id')->references('id')->on('locale_posts')->constrained()->onDelete('cascade');

            $table->unsignedBigInteger('ka_post_id');
            $table->foreign('ka_post_id')->references('id')->on('locale_posts')->constrained()->onDelete('cascade');

            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

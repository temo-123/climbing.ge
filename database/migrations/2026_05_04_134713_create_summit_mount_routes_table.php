<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summit_mount_routes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('summit_id');
            $table->unsignedBigInteger('article_id');
            $table->timestamps();

            $table->foreign('summit_id')->references('id')->on('summits')->cascadeOnDelete();
            $table->foreign('article_id')->references('id')->on('articles')->cascadeOnDelete();
            $table->unique('article_id'); // one route can only belong to one summit
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_mount_routes');
    }
};

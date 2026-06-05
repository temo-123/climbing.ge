<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('summit_ascent_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ascent_id');
            $table->foreign('ascent_id')->references('id')->on('summit_ascents')->cascadeOnDelete();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('summit_ascent_users');
    }
};

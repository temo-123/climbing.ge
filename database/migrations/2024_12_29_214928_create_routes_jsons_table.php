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
        Schema::create('routes_jsons', function (Blueprint $table) {
            $table->id();
            $table->json('json');

            $table->unsignedBigInteger('route_id')->unique();
            $table->foreign('route_id')->references('id')->on('routes');

            $table->unsignedBigInteger('sector_image_id');
            $table->foreign('sector_image_id')->references('id')->on('sector_images');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('route_lines');
    }
};

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
        Schema::create('sector_local_images_jsons', function (Blueprint $table) {
            $table->id();

            $table->json('json');

            $table->unsignedBigInteger('sector_local_image_id')->unique();
            $table->foreign('sector_local_image_id')->references('id')->on('sector_local_images');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sector_local_images_jsons');
    }
};

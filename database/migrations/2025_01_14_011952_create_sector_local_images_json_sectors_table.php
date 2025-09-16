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
        Schema::create('sector_local_images_json_sectors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('sector_local_image_json_id')->unique();
            $table->foreign('sector_local_image_json_id')->references('id')->on('sector_local_images_jsons');

            $table->foreignId('ice_sector_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sector_local_images_json_sectors');
    }
};

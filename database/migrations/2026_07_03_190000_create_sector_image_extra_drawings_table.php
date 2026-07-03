<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sector_image_extra_drawings', function (Blueprint $table) {
            $table->id();
            $table->longText('json')->nullable();
            $table->unsignedInteger('canvas_width')->nullable();
            $table->unsignedInteger('canvas_height')->nullable();
            $table->foreignId('sector_image_id')->unique()->constrained('sector_images')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sector_image_extra_drawings');
    }
};

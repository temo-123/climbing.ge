<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Same idea as SectorLocalImageExtraDrawing, for the spot_rocks_image side —
// a general annotation layer tied only to the image (not to any one sector
// drawn on it, unlike SpotRocksImageJson which varies per sector).
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spot_rocks_image_extra_drawings', function (Blueprint $table) {
            $table->id();
            $table->longText('json')->nullable();
            $table->unsignedInteger('canvas_width')->nullable();
            $table->unsignedInteger('canvas_height')->nullable();
            $table->float('bg_left')->nullable();
            $table->float('bg_top')->nullable();
            $table->float('bg_width')->nullable();
            $table->float('bg_height')->nullable();
            $table->foreignId('spot_rocks_image_id')->unique()->constrained('spot_rocks_images')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spot_rocks_image_extra_drawings');
    }
};

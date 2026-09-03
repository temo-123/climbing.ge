<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Adds a drawing (route/approach-line) layer on top of the EXISTING
// spot_rocks_images gallery — no new image entity. Real close to how
// sector_local_images_jsons works: one photo can show MULTIPLE sectors, each
// with its own independent drawing on the same shared image — except here
// there's no separate "which sectors are linked" pivot to manage, since a
// spot_rocks_image already belongs to exactly one article (its own
// article_id), and that article's Sectors (Sector::article_id) are simply
// the full list of sectors this photo can be drawn for.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spot_rocks_image_jsons', function (Blueprint $table) {
            $table->id();

            $table->longText('json')->nullable();

            $table->foreignId('spot_rocks_image_id')->constrained('spot_rocks_images')->onDelete('cascade');
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('cascade');

            // Paper.js view size + the background photo's own position/size
            // within that view at save time — see CANVAS.md / CanvasManager.vue
            // for why the photo can't be assumed to start at (0,0) or exactly
            // fill the view.
            $table->unsignedInteger('canvas_width')->nullable();
            $table->unsignedInteger('canvas_height')->nullable();
            $table->float('bg_left')->nullable();
            $table->float('bg_top')->nullable();
            $table->float('bg_width')->nullable();
            $table->float('bg_height')->nullable();

            $table->timestamps();

            $table->unique(['spot_rocks_image_id', 'sector_id'], 'uq_spot_rocks_image_sector');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spot_rocks_image_jsons');
    }
};

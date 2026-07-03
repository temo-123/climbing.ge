<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// The editor's Paper.js view fits the background photo with a uniform COVER
// scale, centered in the view — the photo does not necessarily start at
// view-space (0,0) or exactly fill the view's own width/height (see
// CanvasManager.vue's loadBackgroundRaster). Every drawing viewer was
// rescaling strokes using canvas_width/canvas_height (the raw view size)
// alone, silently assuming zero offset — which is exactly what let saved
// strokes land in the wrong place once redrawn over the full-resolution
// photo elsewhere. These four columns record the photo's own actual
// position + size within that view, in the same units the strokes
// themselves are recorded in, so every viewer can apply the real
// translate + scale instead of guessing there's no offset.
return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'climbing_routes_jsons',
            'sector_local_images_jsons',
            'sector_image_extra_drawings',
            'sector_local_image_extra_drawings',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->float('bg_left')->nullable()->after('canvas_height');
                $t->float('bg_top')->nullable()->after('bg_left');
                $t->float('bg_width')->nullable()->after('bg_top');
                $t->float('bg_height')->nullable()->after('bg_width');
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'climbing_routes_jsons',
            'sector_local_images_jsons',
            'sector_image_extra_drawings',
            'sector_local_image_extra_drawings',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['bg_left', 'bg_top', 'bg_width', 'bg_height']);
            });
        }
    }
};

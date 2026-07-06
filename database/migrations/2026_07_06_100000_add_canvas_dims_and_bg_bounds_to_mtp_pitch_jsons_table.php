<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// mtp_pitch_jsons predates the canvas_width/canvas_height + bg_left/bg_top/bg_width/bg_height
// tracking added to the other drawing-JSON tables (see 2026_07_02_193020_add_canvas_dims_to_climbing_routes_jsons_table.php
// and 2026_07_03_210000_add_bg_bounds_to_drawing_json_tables.php) — pitch drawings were left
// out of both, so a pitch's saved strokes could never be rescaled to match the current
// background fit when reopened in a differently-sized container.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mtp_pitch_jsons', function (Blueprint $t) {
            $t->unsignedInteger('canvas_width')->nullable()->after('json');
            $t->unsignedInteger('canvas_height')->nullable()->after('canvas_width');
            $t->float('bg_left')->nullable()->after('canvas_height');
            $t->float('bg_top')->nullable()->after('bg_left');
            $t->float('bg_width')->nullable()->after('bg_top');
            $t->float('bg_height')->nullable()->after('bg_width');
        });
    }

    public function down(): void
    {
        Schema::table('mtp_pitch_jsons', function (Blueprint $t) {
            $t->dropColumn(['canvas_width', 'canvas_height', 'bg_left', 'bg_top', 'bg_width', 'bg_height']);
        });
    }
};

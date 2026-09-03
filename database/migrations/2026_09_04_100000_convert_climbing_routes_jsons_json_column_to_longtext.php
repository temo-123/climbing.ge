<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

// climbing_routes_jsons.json was the only drawing table left storing Paper.js
// data in a native MySQL JSON column — every sibling table (mtp_pitch_jsons,
// sector_local_images_jsons, spot_rocks_image_jsons, the two *_extra_drawings
// tables) already uses LONGTEXT. sector_local_images_jsons was switched away
// from JSON for exactly this table's remaining case: MySQL's JSON type
// auto-validates/re-encodes on every write, which doesn't mix well with our
// storage convention of a DOUBLE-encoded string (JSON.stringify(paper.export
// JSON()) saved as-is, then JSON.parse'd twice on read) — see
// 2026_06_27_002515_add_canvas_size_to_sector_local_images_jsons.php's own
// comment. Converting for consistency ahead of eventually extracting this
// whole drawing subsystem into its own package. LONGTEXT NULL accepts a
// strict superset of what JSON NOT NULL did, so this is safe with existing data.
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE climbing_routes_jsons MODIFY json LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE climbing_routes_jsons MODIFY json JSON NOT NULL');
    }
};

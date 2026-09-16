<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// 2026_06_26_225458_drop_unique_from_sector_local_images_jsons.php correctly
// dropped the old single-column unique on `sector_local_image_id` (it
// predates multi-sector-per-image support), but never replaced it with a
// composite unique on (sector_local_image_id, sector_id) — the actual
// "one drawing per sector per image" invariant `SectorLocalImagesController`'s
// save endpoint relies on via app-level updateOrCreate(). Its sibling table
// `spot_rocks_image_jsons` got this composite unique from day one (see
// 2026_09_03_130000_create_spot_rocks_image_jsons_table.php's
// `uq_spot_rocks_image_sector`) — this restores parity and gives a real DB-
// level backstop against duplicate rows for the same sector (e.g. from a
// race between two near-simultaneous save requests), instead of relying
// entirely on non-atomic app logic.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->unique(['sector_local_image_id', 'sector_id'], 'uq_sector_local_image_sector');
        });
    }

    public function down(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->dropUnique('uq_sector_local_image_sector');
        });
    }
};

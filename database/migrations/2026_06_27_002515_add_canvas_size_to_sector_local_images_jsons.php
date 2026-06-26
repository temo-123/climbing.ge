<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Change json column from JSON type to TEXT to avoid MySQL JSON column casting issues
        //    (MySQL JSON auto-validates/re-encodes which conflicts with our double-encoded storage).
        DB::statement('ALTER TABLE sector_local_images_jsons MODIFY json LONGTEXT NULL');

        // 2. Delete duplicate rows for same (sector_local_image_id, sector_id), keeping the latest.
        DB::statement('
            DELETE s1 FROM sector_local_images_jsons s1
            INNER JOIN sector_local_images_jsons s2
                ON s1.sector_local_image_id = s2.sector_local_image_id
                AND s1.sector_id = s2.sector_id
                AND s1.id < s2.id
        ');

        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            // 3. Add canvas dimensions for correct shape-coordinate scaling on article page.
            $table->unsignedInteger('canvas_width')->nullable()->after('sector_id');
            $table->unsignedInteger('canvas_height')->nullable()->after('canvas_width');

            // 4. Add unique constraint on (sector_local_image_id, sector_id) — one layout per sector.
            //    sector_id was added in a previous migration (2026_06_26_100004).
            $table->unique(['sector_local_image_id', 'sector_id'], 'uq_image_sector');
        });
    }

    public function down(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->dropUnique('uq_image_sector');
            $table->dropColumn(['canvas_width', 'canvas_height']);
        });
        DB::statement('ALTER TABLE sector_local_images_jsons MODIFY json JSON NOT NULL');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// climbing_routes_jsons was the only drawing table whose FKs had no
// onDelete('cascade') — RouteController::deleteOneRoute has to explicitly
// delete the ClimbingRoutesJson row first "to avoid foreign key constraint"
// (its own comment) specifically BECAUSE of this gap; every sibling table
// (mtp_pitch_jsons, sector_local_images_jsons, spot_rocks_image_jsons, the
// two *_extra_drawings tables) already cascades on both its parent FKs.
// Aligning this one too — the app-level cleanup calls stay in place (same
// belt-and-suspenders pattern every other table already has), this just adds
// the DB-level safety net for any path that might miss one.
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->dropForeign('climbing_routes_jsons_route_id_foreign');
            $table->dropForeign('climbing_routes_jsons_sector_image_id_foreign');
        });
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
            $table->foreign('sector_image_id')->references('id')->on('sector_images')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->dropForeign(['route_id']);
            $table->dropForeign(['sector_image_id']);
        });
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->foreign('route_id')->references('id')->on('routes');
            $table->foreign('sector_image_id')->references('id')->on('sector_images');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            // Must drop FK before dropping the index it depends on
            $table->dropForeign(['sector_local_image_id']);
            $table->dropUnique('sector_local_images_jsons_sector_local_image_id_unique');
            // Re-add FK backed by a plain (non-unique) index
            $table->foreign('sector_local_image_id')
                  ->references('id')
                  ->on('sector_local_images')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->dropForeign(['sector_local_image_id']);
            $table->unique('sector_local_image_id');
            $table->foreign('sector_local_image_id')
                  ->references('id')
                  ->on('sector_local_images')
                  ->onDelete('cascade');
        });
    }
};

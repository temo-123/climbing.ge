<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->onDelete('cascade')->after('sector_local_image_id');
        });
    }

    public function down(): void
    {
        Schema::table('sector_local_images_jsons', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
        });
    }
};

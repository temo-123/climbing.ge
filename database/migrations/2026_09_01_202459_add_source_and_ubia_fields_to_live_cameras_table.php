<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('live_cameras', function (Blueprint $table) {
            // 'link'  = existing behaviour, `link` column holds an embeddable/stream URL.
            // 'ubia'  = A8/ucon-style camera, pulled live from UBIA's cloud via `ubia_device_uid`.
            $table->string('source')->default('link')->after('link');
            $table->string('ubia_device_uid')->nullable()->after('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_cameras', function (Blueprint $table) {
            $table->dropColumn(['source', 'ubia_device_uid']);
        });
    }
};

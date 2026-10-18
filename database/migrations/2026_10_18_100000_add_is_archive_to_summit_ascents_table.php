<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Archive ascents: old ascents a logged-in user adds afterwards with their
     * own date/time (never GPS-validated).
     */
    public function up(): void
    {
        Schema::table('summit_ascents', function (Blueprint $table) {
            $table->boolean('is_archive')->default(false)->after('is_gps_validated');
        });
    }

    public function down(): void
    {
        Schema::table('summit_ascents', function (Blueprint $table) {
            $table->dropColumn('is_archive');
        });
    }
};

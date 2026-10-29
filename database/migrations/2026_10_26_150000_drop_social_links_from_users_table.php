<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A climber's social / personal links are the "Sites" list (user_sites,
     * one row per URL — shown on the climber profile with a brand icon), so
     * the separate users.social_links JSON field (2026_07_20) is dropped. No
     * user had data in it.
     */
    public function up(): void
    {
        if (Schema::hasColumn('users', 'social_links')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('social_links');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('users', 'social_links')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('social_links')->nullable()->after('my_bio');
            });
        }
    }
};

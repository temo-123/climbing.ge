<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * When the "confirm your reservation" mail was last sent and how many times
 * (first send + admin resends) — shown in the admin details modal timeline.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_reservations', 'verify_sent_at')) {
                $table->timestamp('verify_sent_at')->nullable()->after('verify_method');
            }
            if (!Schema::hasColumn('tour_reservations', 'verify_sent_count')) {
                $table->unsignedSmallInteger('verify_sent_count')->default(0)->after('verify_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            foreach (['verify_sent_count', 'verify_sent_at'] as $col) {
                if (Schema::hasColumn('tour_reservations', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tour reservations (docs/FUNCTIONS_DOCS/shop/tour_guides.md#fee--completion):
 *   tour_language       language of the tour the guest asked for: ka | ru | en (required in the form)
 *   completed_at        the tour took place (marked by an admin) — for every reservation
 *   commission_percent  climbing.ge fee when the reservation is with a guide who is NOT the
 *                       tour's general guide (15 %, snapshot at the switch); NULL = no fee
 *   commission_amount   percent × price_total (NULL when the price is "on request")
 *   commission_paid_at  the guide paid the fee to climbing.ge
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_reservations', 'tour_language')) {
                $table->string('tour_language', 2)->nullable()->after('persons');
            }
            if (!Schema::hasColumn('tour_reservations', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('verify_sent_count');
            }
            if (!Schema::hasColumn('tour_reservations', 'commission_percent')) {
                $table->decimal('commission_percent', 5, 2)->nullable()->after('completed_at');
            }
            if (!Schema::hasColumn('tour_reservations', 'commission_amount')) {
                $table->decimal('commission_amount', 10, 2)->nullable()->after('commission_percent');
            }
            if (!Schema::hasColumn('tour_reservations', 'commission_paid_at')) {
                $table->timestamp('commission_paid_at')->nullable()->after('commission_amount');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            foreach (['commission_paid_at', 'commission_amount', 'commission_percent', 'completed_at', 'tour_language'] as $col) {
                if (Schema::hasColumn('tour_reservations', $col)) $table->dropColumn($col);
            }
        });
    }
};

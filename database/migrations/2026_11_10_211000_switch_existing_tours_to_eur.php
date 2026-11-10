<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tour prices are calculated in euro (TourPriceService::CURRENCIES = ['EUR']):
 * switch every existing tour to EUR. Idempotent — safe to run again.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('tours', 'price_currency')) {
            DB::table('tours')->where(function ($q) {
                $q->whereNull('price_currency')->orWhere('price_currency', '!=', 'EUR');
            })->update(['price_currency' => 'EUR']);
        }
    }

    public function down(): void
    {
        // nothing to undo: the old value (GEL default) was never a real choice
    }
};

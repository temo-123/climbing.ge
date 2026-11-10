<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tour prices are calculated in euro: new tours default to EUR.
 * Existing rows are switched with plain SQL (see docs/FUNCTIONS_DOCS/shop/tour_price_calculator.md):
 *   UPDATE tours SET price_currency = 'EUR';
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('price_currency', 3)->default('EUR')->change();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->string('price_currency', 3)->default('GEL')->change();
        });
    }
};

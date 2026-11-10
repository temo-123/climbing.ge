<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tour price calculator (docs/FUNCTIONS_DOCS/shop/tour_price_calculator.md)
 *
 * tour_prices — one row = one price rule of a tour:
 *   days            how long the tour is (1 = one day)
 *   persons_min/max group size the rule is for (max NULL = "and more")
 *   price           amount
 *   price_type      per_person (× persons) | per_group (whole group)
 *
 * tours.price_currency — currency of all the tour's rules (GEL / USD / EUR)
 *
 * tour_reservations — what the reserver chose + the price at that moment
 *   (days, price_total, price_currency, price_details = JSON snapshot of the rule).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('tour_prices')) {
            Schema::create('tour_prices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tour_id');
                $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
                $table->unsignedSmallInteger('days')->default(1);
                $table->unsignedSmallInteger('persons_min')->default(1);
                $table->unsignedSmallInteger('persons_max')->nullable();
                $table->decimal('price', 10, 2);
                $table->string('price_type', 20)->default('per_person');
                $table->timestamps();

                $table->index(['tour_id', 'days']);
            });
        }

        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'price_currency')) {
                $table->string('price_currency', 3)->default('GEL')->after('min_price');
            }
        });

        Schema::table('tour_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_reservations', 'days')) {
                $table->unsignedSmallInteger('days')->nullable()->after('persons');
            }
            if (!Schema::hasColumn('tour_reservations', 'price_total')) {
                $table->decimal('price_total', 10, 2)->nullable()->after('days');
            }
            if (!Schema::hasColumn('tour_reservations', 'price_currency')) {
                $table->string('price_currency', 3)->nullable()->after('price_total');
            }
            if (!Schema::hasColumn('tour_reservations', 'price_details')) {
                $table->json('price_details')->nullable()->after('price_currency');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            foreach (['price_details', 'price_currency', 'price_total', 'days'] as $col) {
                if (Schema::hasColumn('tour_reservations', $col)) $table->dropColumn($col);
            }
        });
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'price_currency')) $table->dropColumn('price_currency');
        });
        Schema::dropIfExists('tour_prices');
    }
};

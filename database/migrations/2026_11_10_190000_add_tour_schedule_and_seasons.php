<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tour season + duration schedule (docs/FUNCTIONS_DOCS/shop/tour_schedule.md)
 *
 * tours
 *   all_year          1 = can be booked any time of the year, 0 = only inside tour_seasons
 *   duration_mode     NULL = durations from the price list, 'fixed' = duration_days list,
 *                     'range' = duration_min … duration_max days
 *   duration_days     JSON list of allowed durations for 'fixed' (e.g. [1,2,3,5])
 *   duration_min/max  for 'range'
 *   start_weekdays    JSON list of allowed start weekdays 0 = Sunday … 6 = Saturday, NULL = any day
 *   booking_lead_days book at least N days ahead (0 = today is fine)
 *
 * tour_seasons — yearly periods (month/day, repeats every year; Dec → Mar wraps the new year)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'all_year')) {
                $table->boolean('all_year')->default(true)->after('price_currency');
            }
            if (!Schema::hasColumn('tours', 'duration_mode')) {
                $table->string('duration_mode', 10)->nullable()->after('all_year');
            }
            if (!Schema::hasColumn('tours', 'duration_days')) {
                $table->json('duration_days')->nullable()->after('duration_mode');
            }
            if (!Schema::hasColumn('tours', 'duration_min')) {
                $table->unsignedSmallInteger('duration_min')->nullable()->after('duration_days');
            }
            if (!Schema::hasColumn('tours', 'duration_max')) {
                $table->unsignedSmallInteger('duration_max')->nullable()->after('duration_min');
            }
            if (!Schema::hasColumn('tours', 'start_weekdays')) {
                $table->json('start_weekdays')->nullable()->after('duration_max');
            }
            if (!Schema::hasColumn('tours', 'booking_lead_days')) {
                $table->unsignedSmallInteger('booking_lead_days')->default(0)->after('start_weekdays');
            }
        });

        if (!Schema::hasTable('tour_seasons')) {
            Schema::create('tour_seasons', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('tour_id');
                $table->foreign('tour_id')->references('id')->on('tours')->onDelete('cascade');
                $table->unsignedTinyInteger('from_month');
                $table->unsignedTinyInteger('from_day');
                $table->unsignedTinyInteger('to_month');
                $table->unsignedTinyInteger('to_day');
                $table->timestamps();

                $table->index('tour_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_seasons');
        Schema::table('tours', function (Blueprint $table) {
            foreach (['booking_lead_days', 'start_weekdays', 'duration_max', 'duration_min', 'duration_days', 'duration_mode', 'all_year'] as $col) {
                if (Schema::hasColumn('tours', $col)) $table->dropColumn($col);
            }
        });
    }
};

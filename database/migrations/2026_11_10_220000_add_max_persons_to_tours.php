<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Largest group a tour takes (reservation form persons stepper + server check).
 * NULL = no limit (the form's general maximum, 100). Set in the "Season & duration" tab.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (!Schema::hasColumn('tours', 'max_persons')) {
                $table->unsignedSmallInteger('max_persons')->nullable()->after('booking_lead_days');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            if (Schema::hasColumn('tours', 'max_persons')) $table->dropColumn('max_persons');
        });
    }
};

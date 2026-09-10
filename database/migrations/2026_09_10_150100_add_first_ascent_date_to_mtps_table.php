<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * String, not date — same convention as routes.creation_data — so a
     * partial date (bare "YYYY" or "YYYY-MM") can be stored when the exact
     * day of the first ascent isn't known, not just a full calendar date.
     */
    public function up(): void
    {
        Schema::table('mtps', function (Blueprint $table) {
            $table->string('first_ascent_date')->nullable()->after('first_ascent');
        });
    }

    public function down(): void
    {
        Schema::table('mtps', function (Blueprint $table) {
            $table->dropColumn('first_ascent_date');
        });
    }
};

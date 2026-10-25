<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Parking / approach start for outdoor, ice and indoor articles: "lat,lng"
     * or a Google Maps link. Powers the "Directions" button on the area page.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('parking', 500)->nullable()->after('map');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('parking');
        });
    }
};

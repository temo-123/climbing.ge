<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shiped_countries', function (Blueprint $table) {
            Schema::rename('shiped_countries', 'shiped_regions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiped_countries', function (Blueprint $table) {
            Schema::rename('shiped_regions', 'shiped_countries');
        });
    }
};

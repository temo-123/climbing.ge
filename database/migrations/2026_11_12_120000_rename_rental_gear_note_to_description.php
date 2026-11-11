<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * rental_gear.note → short description shown in the gear lists (admin + shop price list).
 * The existing notes are English, so `note` is renamed to `us_description` (data kept as is)
 * and an empty `ka_description` is added. docs/FUNCTIONS_DOCS/shop/gear_rental.md
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rental_gear', function (Blueprint $table) {
            $table->renameColumn('note', 'us_description');
        });
        Schema::table('rental_gear', function (Blueprint $table) {
            $table->text('ka_description')->nullable()->after('us_description');
        });
    }

    public function down(): void
    {
        Schema::table('rental_gear', function (Blueprint $table) {
            $table->dropColumn('ka_description');
        });
        Schema::table('rental_gear', function (Blueprint $table) {
            $table->renameColumn('us_description', 'note');
        });
    }
};

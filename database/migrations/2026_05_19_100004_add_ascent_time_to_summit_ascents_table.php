<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('summit_ascents', function (Blueprint $table) {
            $table->time('ascent_time')->nullable()->after('ascent_date');
        });
    }

    public function down(): void
    {
        Schema::table('summit_ascents', function (Blueprint $table) {
            $table->dropColumn('ascent_time');
        });
    }
};

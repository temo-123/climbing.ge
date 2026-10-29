<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indoor gym price shown instead of the minimal price:
     * no_info (null = price_from as entered).
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('price_status', 32)->nullable()->after('price_from');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('price_status');
        });
    }
};

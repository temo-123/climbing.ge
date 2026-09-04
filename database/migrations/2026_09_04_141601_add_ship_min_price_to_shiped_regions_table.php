<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shiped_regions', function (Blueprint $table) {
            $table->string('ship_min_price')->nullable()->after('shiping_price');
        });
    }

    public function down(): void
    {
        Schema::table('shiped_regions', function (Blueprint $table) {
            $table->dropColumn('ship_min_price');
        });
    }
};

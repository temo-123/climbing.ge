<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_order_addresses', function (Blueprint $table) {
            $table->foreignId('region_id')->nullable()->after('city')->constrained('shiped_regions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_addresses', function (Blueprint $table) {
            $table->dropForeign(['region_id']);
            $table->dropColumn('region_id');
        });
    }
};

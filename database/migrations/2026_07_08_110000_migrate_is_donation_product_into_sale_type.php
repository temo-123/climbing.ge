<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * "Donation" and "outlet" are just more sale_type values now (alongside
 * online_order/produced_by_order/custom_production), not separate boolean
 * columns — one dropdown, one field, mutually exclusive. Outlet never
 * shipped with its own column (added and rolled back in the same session),
 * but is_donation_product is a real pre-existing column with real data, so
 * this preserves it: any product currently flagged is_donation_product = 1
 * gets sale_type = 'donation' before the column is dropped.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')->where('is_donation_product', 1)->update(['sale_type' => 'donation']);

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_donation_product');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('is_donation_product')->nullable()->after('made_in_georgia');
        });

        DB::table('products')->where('sale_type', 'donation')->update(['is_donation_product' => 1]);
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Which warehouse a line's stock was actually taken from when the order was
 * placed (OrderPlacementService). NULL = no stock was reserved (made-to-order
 * item, production task, or a line created before this column existed), so
 * releasing an order's stock (failed online payment) only gives back what
 * was really taken — and only once, since release clears it again.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('order_products', 'stock_warehouse_id')) return;

        Schema::table('order_products', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_warehouse_id')->nullable()->after('product_option_id');
        });
    }

    public function down(): void
    {
        if (!Schema::hasColumn('order_products', 'stock_warehouse_id')) return;

        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('stock_warehouse_id');
        });
    }
};

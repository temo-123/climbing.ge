<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Snapshot of a line's sale discount at order time (OrderPlacementService):
 *  - item_price    (existing) — what one unit was actually charged
 *  - list_price    (new)      — the option's regular price before its own sale discount
 *  - item_discount (new)      — that sale discount in % (product_options.discount)
 * so order details / invoices can show "55.00 → 52.25 (−5%)" and a later
 * discount change never alters past orders. NULL on lines placed before this.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            if (!Schema::hasColumn('order_products', 'list_price')) {
                $table->decimal('list_price', 10, 2)->nullable()->after('item_price');
            }
            if (!Schema::hasColumn('order_products', 'item_discount')) {
                $table->decimal('item_discount', 5, 2)->nullable()->after('list_price');
            }
        });
    }

    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            foreach (['item_discount', 'list_price'] as $col) {
                if (Schema::hasColumn('order_products', $col)) $table->dropColumn($col);
            }
        });
    }
};

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
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('item_price')->nullable()->after('quantity');
            $table->integer('total_price')->nullable()->after('quantity');

            $table->foreignId('product_id', 50)->nullable()->change();
            $table->foreignId('product_option_id', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('item_price');
            $table->dropColumn('total_price');

            $table->foreignId('product_id', 50)->nullable(false)->change();
            $table->foreignId('product_option_id', 50)->nullable(false)->change();
        });
    }
};

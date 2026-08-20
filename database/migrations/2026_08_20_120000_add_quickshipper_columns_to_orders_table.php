<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('quickshipper_shipment_id')->nullable()->after('warehouse_id');
            $table->string('quickshipper_tracking_number')->nullable()->after('quickshipper_shipment_id');
            $table->string('quickshipper_label_url')->nullable()->after('quickshipper_tracking_number');
            $table->string('quickshipper_status', 50)->nullable()->after('quickshipper_label_url');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'quickshipper_shipment_id',
                'quickshipper_tracking_number',
                'quickshipper_label_url',
                'quickshipper_status',
            ]);
        });
    }
};

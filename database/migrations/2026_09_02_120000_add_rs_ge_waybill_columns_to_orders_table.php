<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('rs_ge_waybill_id')->nullable()->after('quickshipper_status');
            $table->string('rs_ge_waybill_number')->nullable()->after('rs_ge_waybill_id');
            $table->string('rs_ge_waybill_status')->nullable()->after('rs_ge_waybill_number');
            $table->timestamp('rs_ge_waybill_sent_at')->nullable()->after('rs_ge_waybill_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['rs_ge_waybill_id', 'rs_ge_waybill_number', 'rs_ge_waybill_status', 'rs_ge_waybill_sent_at']);
        });
    }
};

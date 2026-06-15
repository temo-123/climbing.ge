<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('tbc_pay_id')->nullable()->after('flitt_order_id');
            $table->string('tbc_pay_status', 50)->nullable()->after('tbc_pay_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['tbc_pay_id', 'tbc_pay_status']);
        });
    }
};

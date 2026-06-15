<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tbc_pay_id')->nullable()->after('payment');
            $table->string('tbc_pay_status', 50)->nullable()->after('tbc_pay_id');
            $table->decimal('payment_amount', 10, 2)->nullable()->after('tbc_pay_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tbc_pay_id', 'tbc_pay_status', 'payment_amount']);
        });
    }
};

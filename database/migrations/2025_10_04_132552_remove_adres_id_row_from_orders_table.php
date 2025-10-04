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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_order_costome')->default(false)->after('shiping');

            $table->dropColumn('status_updating_data');
            $table->dropColumn('status');

            $table->dropForeign(['adres_id']); 
            $table->dropColumn('adres_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_order_costome');

            $table->string('status')->nullable()->after('shiping');
            $table->timestamp('status_updating_data')->nullable()->after('shiping');

            $table->unsignedBigInteger('adres_id');
            $table->foreign('adres_id')->references('id')->on('user_adreses');
        });
    }
};

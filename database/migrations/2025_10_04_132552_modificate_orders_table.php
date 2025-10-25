<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_order_costome');
            $table->boolean('is_custom')->default(false)->after('shiping');

            $table->dropColumn('status_updating_data');
            $table->dropColumn('status');

            $table->dropForeign(['adres_id']);
            $table->dropColumn('adres_id');

            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_custom');
            $table->boolean('is_order_costome')->default(false)->after('shiping');

            $table->string('status')->nullable()->after('shiping');
            $table->timestamp('status_updating_data')->nullable()->after('shiping');

            $table->unsignedBigInteger('adres_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
        });

        // Clean invalid foreign key data before adding constraints
        DB::statement("UPDATE orders SET adres_id = NULL WHERE adres_id IS NOT NULL AND adres_id NOT IN (SELECT id FROM user_adreses)");
        DB::statement("UPDATE orders SET user_id = NULL WHERE user_id IS NOT NULL AND user_id NOT IN (SELECT id FROM users)");

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('adres_id')->references('id')->on('user_adreses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};

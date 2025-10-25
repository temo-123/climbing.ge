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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('favorite_outdoor');
            $table->dropColumn('favorite_product');
            $table->dropColumn('favorite_film');
            $table->dropColumn('interested_event');

            $table->dropColumn('news');
            $table->dropColumn('add_new_sector');
            $table->dropColumn('add_new_outdoor_spot');
            $table->dropColumn('add_new_ice_spot');
            $table->dropColumn('add_new_techtip');
            $table->dropColumn('add_new_gym');
            $table->dropColumn('add_new_product');
            $table->dropColumn('add_new_service');

            $table->integer('notification_type')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->integer('favorite_outdoor')->nullable()->after('id');
            $table->integer('favorite_product')->nullable()->after('id');
            $table->integer('favorite_film')->nullable()->after('id');
            $table->integer('interested_event')->nullable()->after('id');

            $table->integer('news')->nullable()->after('id');
            $table->integer('add_new_sector')->nullable()->after('id');
            $table->integer('add_new_outdoor_spot')->nullable()->after('id');
            $table->integer('add_new_ice_spot')->nullable()->after('id');
            $table->integer('add_new_techtip')->nullable()->after('id');
            $table->integer('add_new_gym')->nullable()->after('id');
            $table->integer('add_new_product')->nullable()->after('id');
            $table->integer('add_new_service')->nullable()->after('id');

            $table->dropColumn('notification_type');
        });
    }
};

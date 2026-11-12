<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * use_offline_data_on_mobile: boolean → nullable timestamp. The mobile app now
     * sends the time of the user's last offline-data upload; NULL = never / not using
     * offline data. The old boolean only ever held the default `false`, so the column
     * is recreated instead of converted.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('use_offline_data_on_mobile');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('use_offline_data_on_mobile')->nullable()->after('animations');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('use_offline_data_on_mobile');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('use_offline_data_on_mobile')->default(false)->after('animations');
        });
    }
};

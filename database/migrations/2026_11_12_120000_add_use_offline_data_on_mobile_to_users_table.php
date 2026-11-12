<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mobile app preference: keep guidebook / training data on the device for
     * offline use. Set from the app via get_options/update_preferences.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('use_offline_data_on_mobile')->default(false)->after('animations');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('use_offline_data_on_mobile');
        });
    }
};

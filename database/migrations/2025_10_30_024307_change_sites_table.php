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
        Schema::table('sites', function (Blueprint $table) {
            $table->dropForeign(['us_info_id']);
            $table->dropColumn('us_info_id');

            $table->dropForeign(['ka_info_id']);
            $table->dropColumn('ka_info_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->unsignedBigInteger('us_info_id');
            $table->foreign('us_info_id')->references('id')->on('locale_sites')->onDelete('cascade');

            $table->unsignedBigInteger('ka_info_id');
            $table->foreign('ka_info_id')->references('id')->on('locale_sites')->onDelete('cascade');
        });
    }
};

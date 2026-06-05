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
        Schema::table('summits', function (Blueprint $table) {
            if (Schema::hasColumn('summits', 'region_id')) {
                $table->dropForeign(['region_id']);
                $table->dropColumn('region_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('summits', function (Blueprint $table) {
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
        });
    }
};

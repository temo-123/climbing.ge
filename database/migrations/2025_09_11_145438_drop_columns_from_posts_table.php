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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['topic_id_foreign']);
            $table->dropColumn('sector_id', 'route_id', 'mtp_id', 'region_id', 'topic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('sector_id')->nullable();
            $table->string('route_id')->nullable();
            $table->string('mtp_id')->nullable();
            $table->string('region_id')->nullable();

            $table->foreignId('topic_id')->constrained();
        });
    }
};

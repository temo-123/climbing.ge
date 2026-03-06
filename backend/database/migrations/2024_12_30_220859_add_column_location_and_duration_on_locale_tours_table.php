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
        Schema::table('locale_tours', function (Blueprint $table) {
            $table->string('location')->nullable()->after('text');
            $table->string('duration')->nullable()->after('text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locale_tours', function (Blueprint $table) {
            $table->dropColumn(['location', 'duration']);
        });
    }
};

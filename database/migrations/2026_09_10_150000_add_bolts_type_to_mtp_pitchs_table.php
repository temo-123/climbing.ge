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
        Schema::table('mtp_pitchs', function (Blueprint $table) {
            $table->string('bolts_type')->nullable()->after('bolts');
        });
    }

    public function down(): void
    {
        Schema::table('mtp_pitchs', function (Blueprint $table) {
            $table->dropColumn('bolts_type');
        });
    }
};

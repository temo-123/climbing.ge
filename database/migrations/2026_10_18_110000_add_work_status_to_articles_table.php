<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Indoor gym status shown instead of the open/close hours:
     * temporarily_closed | sport_session_closed | coming_soon (null = normal hours).
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('work_status', 32)->nullable()->after('closed_time');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('work_status');
        });
    }
};

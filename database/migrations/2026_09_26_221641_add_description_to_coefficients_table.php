<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-facing note on what a coefficient controls (shown in the
 * Site Options -> Coefficients table and add/edit modals).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            $table->text('description')->nullable()->after('value');
        });
    }

    public function down(): void
    {
        Schema::table('coefficients', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};

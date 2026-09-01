<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_codes', function (Blueprint $table) {
            $table->timestamp('used_at')->nullable()->after('action_data');
        });
    }

    public function down(): void
    {
        Schema::table('sale_codes', function (Blueprint $table) {
            $table->dropColumn('used_at');
        });
    }
};

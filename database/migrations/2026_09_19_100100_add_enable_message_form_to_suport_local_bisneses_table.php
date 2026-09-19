<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suport_local_bisneses', function (Blueprint $table) {
            $table->integer('enable_message_form')->nullable()->after('public_totaly');
        });
    }

    public function down(): void
    {
        Schema::table('suport_local_bisneses', function (Blueprint $table) {
            $table->dropColumn('enable_message_form');
        });
    }
};

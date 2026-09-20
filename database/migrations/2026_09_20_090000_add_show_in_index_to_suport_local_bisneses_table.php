<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suport_local_bisneses', function (Blueprint $table) {
            $table->integer('show_in_index')->nullable()->after('enable_message_form');
        });
    }

    public function down(): void
    {
        Schema::table('suport_local_bisneses', function (Blueprint $table) {
            $table->dropColumn('show_in_index');
        });
    }
};

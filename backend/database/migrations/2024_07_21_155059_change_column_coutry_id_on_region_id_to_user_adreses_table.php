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
        Schema::table('user_adreses', function (Blueprint $table) {
            $table->renameColumn('country_id', 'region_id'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_adreses', function (Blueprint $table) {
            $table->renameColumn('region_id', 'country_id'); 
        });
    }
};

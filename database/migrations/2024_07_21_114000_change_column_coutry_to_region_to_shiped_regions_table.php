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
        Schema::table('shiped_regions', function (Blueprint $table) {
            $table->renameColumn('country', 'region'); 
            // $table->renameColumn('old_column_name', 'new_column_name');       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shiped_regions', function (Blueprint $table) {
            $table->renameColumn('region', 'country'); 
            // $table->renameColumn('new_column_name', 'old_column_name');
        });
    }
};

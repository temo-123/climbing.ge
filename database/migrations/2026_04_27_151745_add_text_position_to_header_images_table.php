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
        Schema::table('header_images', function (Blueprint $table) {
            $table->string('text_position', 20)->default('center')->after('link');
        });
    }

    public function down(): void
    {
        Schema::table('header_images', function (Blueprint $table) {
            $table->dropColumn('text_position');
        });
    }
};

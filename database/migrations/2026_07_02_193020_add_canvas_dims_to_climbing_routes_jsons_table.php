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
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->unsignedInteger('canvas_width')->nullable()->after('json');
            $table->unsignedInteger('canvas_height')->nullable()->after('canvas_width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('climbing_routes_jsons', function (Blueprint $table) {
            $table->dropColumn(['canvas_width', 'canvas_height']);
        });
    }
};

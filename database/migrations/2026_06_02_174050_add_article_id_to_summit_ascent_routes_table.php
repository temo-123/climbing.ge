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
        Schema::table('summit_ascent_routes', function (Blueprint $table) {
            $table->unsignedBigInteger('article_id')->nullable()->after('route_id');
            $table->foreign('article_id')->references('id')->on('articles')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('summit_ascent_routes', function (Blueprint $table) {
            $table->dropForeign(['article_id']);
            $table->dropColumn('article_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Slide sequence number — separate sequence per category (guide / shop).
     */
    public function up(): void
    {
        Schema::table('header_images', function (Blueprint $table) {
            $table->unsignedInteger('sort_order')->default(0)->after('text_position');
            $table->index(['category', 'sort_order']);
        });

        // Number existing slides 1..N inside each category, keeping their current (id) order
        $slides = DB::table('header_images')->orderBy('category')->orderBy('id')->get(['id', 'category']);
        $counters = [];
        foreach ($slides as $slide) {
            $counters[$slide->category] = ($counters[$slide->category] ?? 0) + 1;
            DB::table('header_images')->where('id', $slide->id)->update(['sort_order' => $counters[$slide->category]]);
        }
    }

    public function down(): void
    {
        Schema::table('header_images', function (Blueprint $table) {
            $table->dropIndex(['category', 'sort_order']);
            $table->dropColumn('sort_order');
        });
    }
};

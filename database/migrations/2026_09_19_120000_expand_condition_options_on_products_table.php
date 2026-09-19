<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Expands the outlet product "condition" enum from the original 4 values
 * (new/like_new/used/refurbished) to 7, splitting "used" into "used_good"/
 * "used_fair" and adding "open_box" and "damaged". Existing "used" rows are
 * backfilled to "used_good" before the enum is narrowed, since MySQL enums
 * reject values missing from the new definition.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('products')->where('condition', 'used')->update(['condition' => 'used_good']);

        DB::statement("ALTER TABLE products MODIFY `condition` ENUM('new', 'like_new', 'open_box', 'used_good', 'used_fair', 'refurbished', 'damaged') NULL");
    }

    public function down(): void
    {
        DB::table('products')->whereIn('condition', ['used_good', 'used_fair'])->update(['condition' => 'used']);
        DB::table('products')->whereIn('condition', ['open_box', 'damaged'])->update(['condition' => null]);

        DB::statement("ALTER TABLE products MODIFY `condition` ENUM('new', 'like_new', 'used', 'refurbished') NULL");
    }
};

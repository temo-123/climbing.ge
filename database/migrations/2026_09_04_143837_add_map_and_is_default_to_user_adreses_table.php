<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_adreses', function (Blueprint $table) {
            // 'map' has been in User_adreses::$fillable and referenced by the
            // frontend since before this migration, but the column was never
            // created — every add_user_adreses() call has been throwing a SQL
            // error ("Unknown column 'map'") as a result.
            $table->string('map')->nullable()->after('zip_code');
            $table->boolean('is_default')->default(false)->after('map');
        });
    }

    public function down(): void
    {
        Schema::table('user_adreses', function (Blueprint $table) {
            $table->dropColumn(['map', 'is_default']);
        });
    }
};

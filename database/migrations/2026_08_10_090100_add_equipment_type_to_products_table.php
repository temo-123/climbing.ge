<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->enum('equipment_type', [
                'fingerboard', 'campus_board', 'climbing_wall',
                'system_wall', 'pull_up_bar', 'weights',
            ])->nullable()->after('subcategory_id');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('equipment_type');
        });
    }
};

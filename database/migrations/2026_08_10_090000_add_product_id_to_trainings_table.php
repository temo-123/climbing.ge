<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable()->after('id');
            $table->foreign('product_id')->references('id')->on('products')->nullOnDelete();
            $table->index('product_id', 'idx_trainings_product');
        });
    }

    public function down(): void
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropIndex('idx_trainings_product');
            $table->dropColumn('product_id');
        });
    }
};

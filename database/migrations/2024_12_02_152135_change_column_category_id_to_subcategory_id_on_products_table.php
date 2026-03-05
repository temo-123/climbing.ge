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
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);

            $table->dropColumn('category_id');
            $table->unsignedBigInteger('subcategory_id')->nullable();

            // $table->renameColumn('category_id', 'subcategory_id');

            $table->foreign('subcategory_id')->references('id')->on('product_subcategories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['subcategory_id']);

            $table->dropColumn('subcategory_id');
            $table->unsignedBigInteger('category_id')->nullable();

            // $table->renameColumn('subcategory_id', 'category_id'); 

            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }
};

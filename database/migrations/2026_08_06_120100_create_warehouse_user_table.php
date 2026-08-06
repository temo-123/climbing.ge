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
        Schema::create('warehouse_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            // unique = a user can be assigned to at most one warehouse
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_user');
    }
};

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
        Schema::dropIfExists('order_statuses');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('status')->nullable();

            $table->timestamp('status_updating_data')->nullable();

            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }
};

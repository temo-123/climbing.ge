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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('country')->nullable();
            $table->integer('age')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('GEL');
            $table->string('status')->default('pending');
            $table->string('flitt_order_id')->nullable();
            $table->string('response_url')->nullable();
            $table->string('server_callback_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};


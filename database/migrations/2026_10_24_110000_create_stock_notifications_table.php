<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * "Notify me when it's back in stock" on a product option. The hourly
     * app:send-back-in-stock command emails the user once the option has
     * stock again and sets notified_at.
     */
    public function up(): void
    {
        Schema::create('stock_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('option_id');
            $table->foreign('option_id')->references('id')->on('product_options')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('locale', 2)->default('us');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'option_id']);
            $table->index('notified_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_notifications');
    }
};

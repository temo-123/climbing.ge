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
        Schema::dropIfExists('topics');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('topics', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('us_name')->nullable();
            // $table->string('ru_name')->nullable();
            $table->string('ka_name')->nullable();

            $table->timestamps();
        });
    }
};

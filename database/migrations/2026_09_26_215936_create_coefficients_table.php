<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Admin-editable numeric coefficients (wall calculator prices, delivery
 * periods, climber points weights, ...), keyed by slug. Read through
 * App\Services\CoefficientService, which falls back to config/coefficients.php
 * for any slug without a row here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coefficients', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->decimal('value', 14, 4)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coefficients');
    }
};

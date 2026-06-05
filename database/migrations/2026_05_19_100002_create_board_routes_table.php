<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('route_name');
            $table->string('grade', 50)->nullable();
            $table->date('date')->nullable();
            $table->text('holds')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_routes');
    }
};

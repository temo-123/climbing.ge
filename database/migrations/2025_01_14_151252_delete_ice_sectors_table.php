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
        Schema::dropIfExists('ice_sectors');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('ice_sectors', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->nullable();
            $table->text('text')->nullable();

            $table->foreignId('article_id')->constrained();
            
            $table->integer('published')->nullable();
            
            $table->timestamps();
        });
    }
};

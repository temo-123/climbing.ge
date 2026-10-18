<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Addresses used by past orders must outlive the account that created them
     * (account self-deletion keeps order records), so detach instead of blocking.
     */
    public function up(): void
    {
        Schema::table('user_adreses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_adreses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('user_adreses', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('user_adreses', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};

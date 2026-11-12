<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * provider_user_id was INT — Google subject ids are 21-digit numbers (bigger than even
     * BIGINT UNSIGNED) and other providers may return non-numeric ids, so every Google
     * login/sign-up failed with "Out of range value for column 'provider_user_id'".
     */
    public function up(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->string('provider_user_id', 191)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('social_accounts', function (Blueprint $table) {
            $table->integer('provider_user_id')->nullable()->change();
        });
    }
};

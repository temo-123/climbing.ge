<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tour reservation email verification: when (verified_at) and how (verify_method:
 * 'email' = the reserver clicked the link in the mail, 'admin' = verified in the panel).
 * `text` grows to TEXT — the form allows 1000 characters, the old varchar(191) cut them.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('tour_reservations', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verificate');
            }
            if (!Schema::hasColumn('tour_reservations', 'verify_method')) {
                $table->string('verify_method', 20)->nullable()->after('verified_at');
            }
            $table->text('text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('tour_reservations', function (Blueprint $table) {
            if (Schema::hasColumn('tour_reservations', 'verify_method')) {
                $table->dropColumn('verify_method');
            }
            if (Schema::hasColumn('tour_reservations', 'verified_at')) {
                $table->dropColumn('verified_at');
            }
        });
    }
};

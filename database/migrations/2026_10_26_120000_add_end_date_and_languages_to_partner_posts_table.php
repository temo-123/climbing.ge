<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Partner posts become trips: climb_date is the start, end_date the last
     * day (null = a one-day trip). A post stays on the board until the trip
     * is over. languages: the codes the poster speaks (PartnerPost::LANGUAGES).
     */
    public function up(): void
    {
        Schema::table('partner_posts', function (Blueprint $table) {
            $table->date('end_date')->nullable()->after('climb_date');
            $table->json('languages')->nullable()->after('grade_to');
            $table->index(['end_date']);
        });
    }

    public function down(): void
    {
        Schema::table('partner_posts', function (Blueprint $table) {
            $table->dropIndex(['end_date']);
            $table->dropColumn(['end_date', 'languages']);
        });
    }
};

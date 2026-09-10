<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * routes.first_ascent_date was added as a strict `date` column, which
     * can't hold a bare year or year+month — routes.creation_data (the
     * bolting date) is already a plain string for exactly this reason, so
     * this brings first_ascent_date in line with that same convention
     * instead of leaving the two "date" fields on Route inconsistent.
     * Requires doctrine/dbal (already a project dependency) for ->change().
     */
    public function up(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->string('first_ascent_date')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('routes', function (Blueprint $table) {
            $table->date('first_ascent_date')->nullable()->change();
        });
    }
};

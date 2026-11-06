<?php

use App\Services\CoefficientService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Climber points for route ascents (logbook ticks) and MTP ascents — adds
 * the two coefficient rows so they show up in Site Options -> Coefficients.
 * Idempotent: an existing row (e.g. an admin-edited value) is left as is.
 */
return new class extends Migration
{
    private array $rows = [
        'points_route_ascent' => [3, 'Climber points per sport/boulder route ascent'],
        'points_mtp_ascent'   => [8, 'Climber points per MTP (multi-pitch) ascent'],
    ];

    public function up(): void
    {
        $now = now();

        foreach ($this->rows as $slug => [$value, $description]) {
            DB::table('coefficients')->insertOrIgnore([
                'slug'        => $slug,
                'value'       => $value,
                'description' => $description,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]);
        }

        CoefficientService::forget();
    }

    public function down(): void
    {
        DB::table('coefficients')->whereIn('slug', array_keys($this->rows))->delete();

        CoefficientService::forget();
    }
};

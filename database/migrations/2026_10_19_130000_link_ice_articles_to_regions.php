<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Ice articles can now have a region (article_region, same as outdoor / indoor).
 * Links the existing ice spots to their regions, matched by url_title.
 * Idempotent: skips articles that already have a region and missing rows.
 */
return new class extends Migration
{
    // ice article url_title => region us_name
    private array $links = [
        'ice_climbing_in_gveleti_kazbegi'             => 'Stepantsminda (Kazbegi) region',
        'ice_climbing_in_juta'                        => 'Stepantsminda (Kazbegi) region',
        'ice_climbing_in_mleta_near_gudauri'          => 'Stepantsminda (Kazbegi) region',
        'dry_tooling_on_lotkin_monument'              => 'Near Tbilisi',
        'ice_climbing_&_dry_tooling_in_patara_mitarbi' => 'Borjomi Region',
        'dry_tooling_in_katskhi'                      => 'Chiatura region',
        'dry_tooling_in_chiatura_'                    => 'Chiatura region',
        // ice_climbing_in_ateni_valley — no matching region yet, set it in the admin form
    ];

    public function up(): void
    {
        foreach ($this->links as $url_title => $region_name) {
            $article_id = DB::table('articles')->where('category', 'ice')->where('url_title', $url_title)->value('id');
            $region_id = DB::table('regions')->where('us_name', $region_name)->value('id');

            if (!$article_id || !$region_id) {
                continue;
            }
            if (DB::table('article_region')->where('article_id', $article_id)->exists()) {
                continue;
            }

            DB::table('article_region')->insert([
                'article_id' => $article_id,
                'region_id'  => $region_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        foreach ($this->links as $url_title => $region_name) {
            $article_id = DB::table('articles')->where('category', 'ice')->where('url_title', $url_title)->value('id');
            $region_id = DB::table('regions')->where('us_name', $region_name)->value('id');

            if ($article_id && $region_id) {
                DB::table('article_region')->where('article_id', $article_id)->where('region_id', $region_id)->delete();
            }
        }
    }
};

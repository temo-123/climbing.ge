<?php

namespace App\Services;

use Illuminate\Support\Str;

class URLTitleService
{
    /**
     * URL slug from the English title: lowercase ASCII letters, digits and "_" only.
     *
     * The old character-by-character replacement let "+", "#", "?", "!" and non-Latin letters
     * through ("mini_thermometer_+_compass", a Cyrillic "с" in "bouldering_сhampionship…"):
     * "#" / "?" cut the URL, and the rest made canonical / sitemap URLs differ by encoding.
     * Str::slug transliterates (Georgian and Cyrillic too) and collapses separators.
     * Only new slugs (and edits with "change URL title") use this; stored ones stay.
     */
    public static function get_url_title($us_title)
    {
        $slug = Str::slug((string) $us_title, '_', 'en');

        // nothing transliterable (e.g. only symbols): keep a readable fallback
        if ($slug === '') {
            $slug = trim(preg_replace('/[^a-z0-9]+/', '_', mb_strtolower((string) $us_title)), '_');
        }

        return $slug;
    }
}

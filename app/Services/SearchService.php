<?php

namespace App\Services;

use App\Services\Abstract\LocaleContentService;

class SearchService extends LocaleContentService
{
    private static float $fuzzyThreshold = 55.0;
    private static int   $fuzzyLimit     = 8;
    private static int   $fuzzyMinLength = 3;

    public static function normalizeLocale(string $locale): string
    {
        return $locale === 'ka' ? 'ka' : 'us';
    }

    /**
     * Search locale-based content (articles, products, tours, services, posts).
     * Falls back to fuzzy title matching when LIKE yields nothing.
     *
     * @param string        $localeModel    e.g. Locale_article::class
     * @param string        $globalRelBase  Relationship prefix on locale model: 'global_article' → 'global_article_us'
     * @param string        $service        Service class
     * @param string        $serviceMethod  Called as $service::$serviceMethod($globals, $locale)
     * @param string        $query
     * @param string        $locale         'us' or 'ka'
     * @param \Closure|null $globalFilter   Optional extra filter applied after published check
     */
    public static function localeSearch(
        string $localeModel,
        string $globalRelBase,
        string $service,
        string $serviceMethod,
        string $query,
        string $locale = 'us',
        ?\Closure $globalFilter = null
    ): array {
        $locale   = static::normalizeLocale($locale);
        $relation = $globalRelBase . '_' . $locale;

        // Exact LIKE search
        $matches = $localeModel::where('locale', $locale)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            })->get();

        // Sort by relevance: exact > starts-with > word-boundary > contains
        $lq      = mb_strtolower($query);
        $matches = $matches->sortByDesc(function ($item) use ($lq) {
            $title = mb_strtolower($item->title ?? '');
            if ($title === $lq) return 4;
            if (str_starts_with($title, $lq)) return 3;
            if (preg_match('/\b' . preg_quote($lq, '/') . '/iu', $title)) return 2;
            return 1;
        });

        $globals = static::resolvePublished($matches, $relation, $globalFilter);

        if (!empty($globals)) {
            return ['suggested' => false, 'best_match' => null, 'results' => $service::$serviceMethod($globals, $locale)];
        }

        // Skip fuzzy for very short queries — too noisy
        if (mb_strlen($query) < static::$fuzzyMinLength) {
            return ['suggested' => false, 'best_match' => null, 'results' => []];
        }

        // Fuzzy fallback — "maybe you mean this"
        $allLocale  = $localeModel::where('locale', $locale)->get(['id', 'title']);
        $fuzzy      = static::fuzzyFilter($allLocale, $query, 'title');
        $bestMatch  = !empty($fuzzy) ? ($fuzzy[0]->title ?? null) : null;

        if (empty($fuzzy)) {
            return ['suggested' => false, 'best_match' => null, 'results' => []];
        }

        $fuzzyIds     = array_map(fn($i) => $i->id, $fuzzy);
        $fullFuzzy    = $localeModel::whereIn('id', $fuzzyIds)->get();
        $fuzzyGlobals = static::resolvePublished($fullFuzzy, $relation, $globalFilter);

        return [
            'suggested'  => !empty($fuzzyGlobals),
            'best_match' => !empty($fuzzyGlobals) ? $bestMatch : null,
            'results'    => !empty($fuzzyGlobals) ? $service::$serviceMethod($fuzzyGlobals, $locale) : [],
        ];
    }

    /**
     * Search a non-locale model directly (sectors, routes, summits).
     * Falls back to fuzzy matching on the first field when LIKE yields nothing.
     *
     * @param string $model
     * @param array  $fields         Fields to search; first field is used for fuzzy fallback
     * @param string $query
     * @param bool   $checkPublished Whether to filter WHERE published = 1
     */
    public static function directSearch(
        string $model,
        array $fields,
        string $query,
        bool $checkPublished = false
    ): array {
        $base = $model::query()->when($checkPublished, fn($q) => $q->where('published', 1));

        $exact = (clone $base)->where(function ($q) use ($fields, $query) {
            foreach ($fields as $i => $field) {
                $i === 0
                    ? $q->where($field, 'like', "%{$query}%")
                    : $q->orWhere($field, 'like', "%{$query}%");
            }
        })->get();

        if ($exact->isNotEmpty()) {
            // Sort by relevance on primary field
            $lq   = mb_strtolower($query);
            $f0   = $fields[0];
            $exact = $exact->sortByDesc(function ($item) use ($lq, $f0) {
                $val = mb_strtolower($item->$f0 ?? '');
                if ($val === $lq) return 4;
                if (str_starts_with($val, $lq)) return 3;
                if (preg_match('/\b' . preg_quote($lq, '/') . '/iu', $val)) return 2;
                return 1;
            });
            return ['suggested' => false, 'best_match' => null, 'results' => $exact->values()->toArray()];
        }

        // Skip fuzzy for very short queries
        if (mb_strlen($query) < static::$fuzzyMinLength) {
            return ['suggested' => false, 'best_match' => null, 'results' => []];
        }

        // Fuzzy on first field
        $all   = (clone $base)->get(['id', $fields[0]]);
        $fuzzy = static::fuzzyFilter($all, $query, $fields[0]);

        if (empty($fuzzy)) {
            return ['suggested' => false, 'best_match' => null, 'results' => []];
        }

        $bestMatch = !empty($fuzzy) ? ($fuzzy[0]->{$fields[0]} ?? null) : null;
        $ids       = array_map(fn($i) => $i->id, $fuzzy);
        $results   = $model::whereIn('id', $ids)
            ->when($checkPublished, fn($q) => $q->where('published', 1))
            ->get();

        return ['suggested' => true, 'best_match' => $bestMatch, 'results' => $results->toArray()];
    }

    /**
     * Compute similarity scores and return items above threshold, sorted by score desc.
     */
    public static function fuzzyFilter($items, string $query, string $field): array
    {
        $scored = [];
        $lq     = mb_strtolower($query);

        foreach ($items as $item) {
            $val = is_array($item) ? ($item[$field] ?? '') : ($item->$field ?? '');
            if (!$val) continue;

            $lval = mb_strtolower($val);
            similar_text($lq, $lval, $pct);

            // Prefix matches are always relevant regardless of similar_text score
            if (str_starts_with($lval, $lq)) {
                $pct = max($pct, 72.0);
            }

            if ($pct >= static::$fuzzyThreshold) {
                $scored[] = ['item' => $item, 'score' => $pct];
            }
        }

        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

        return array_map(fn($s) => $s['item'], array_slice($scored, 0, static::$fuzzyLimit));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private static function resolvePublished($localeItems, string $globalRelation, ?\Closure $filter = null): array
    {
        $globals = [];
        foreach ($localeItems as $item) {
            $global = $item->$globalRelation;
            if ($global && $global->published == 1) {
                if ($filter === null || $filter($global)) {
                    $globals[] = $global;
                }
            }
        }
        return $globals;
    }

    // -------------------------------------------------------------------------
    // Legacy (backward compatibility — used by old articleSearch/productSearch)
    // -------------------------------------------------------------------------

    public static function universalSearch($model, $service, $service_function, $type, $global_item, $query, $locale = 'us')
    {
        $locale_items = $model::when($query, function ($q, $search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('short_description', 'like', "%$search%");
        })->get();

        $locale_items = $locale_items->where('locale', '=', $locale);

        if ($locale_items->count() != 0) {
            $searching_global_items = [];
            foreach ($locale_items as $locale_item) {
                if ($locale_item->$global_item->published == 1) {
                    array_push($searching_global_items, $locale_item->$global_item);
                }
            }
            return $service::$service_function($searching_global_items, $locale);
        }

        return [];
    }
}

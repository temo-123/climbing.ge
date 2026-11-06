<?php

namespace App\Services\Seo;

use App\Services\UrlRedirectService;
use Illuminate\Support\Facades\DB;

/**
 * Public URL ⇄ content record, for the SEO dashboard and the content quality list.
 *
 *   resolve('https://climbing.ge/ka/outdoor/climbing_in_chiatura')
 *       → ['type' => 'article', 'id' => 4, 'locale' => 'ka']
 *   describe([['article', 4], …]) → title, public URL, admin edit route per record
 *
 * Old slugs resolve through url_redirects, so traffic recorded before a rename still
 * counts for the page.
 */
class ContentResolver
{
    /** article category → URL segment (same as SeoService::ARTICLE_PATHS) */
    public const ARTICLE_PATHS = [
        'outdoor' => 'outdoor', 'mount_route' => 'mountaineering', 'indoor' => 'indoor', 'ice' => 'ice',
        'other' => 'other', 'spot_projects' => 'spot_project', 'partners' => 'partner',
        'special' => 'special_article', 'news' => 'news', 'tech_tip' => 'tech_tip',
    ];

    /** type → [table, URL segment, host config key, admin edit route name|null, us title source] */
    public const TYPES = [
        'event'        => ['events', 'event', 'site_url', 'eventEdit'],
        'local_bisnes' => ['suport_local_bisneses', 'local_bisnes', 'site_url', null],
        'product'      => ['products', 'product', 'shop_url', 'productEdit'],
        'service'      => ['services', 'service', 'shop_url', 'serviceEdit'],
        'tour'         => ['tours', 'tour', 'shop_url', 'tourEdit'],
        'post'         => ['posts', 'post', 'blog_url', 'edit_post'],
        'summit'       => ['summits', 'summit', 'summit_url', 'summitEdit'],
        'film'         => ['films', 'film', 'films_url', null],
    ];

    private ?array $slugs = null;

    public function resolve(string $url): array
    {
        $path   = (string) parse_url($url, PHP_URL_PATH);
        $parts  = array_values(array_filter(array_map('rawurldecode', explode('/', trim($path, '/'))), 'strlen'));
        $locale = ($parts[0] ?? '') === 'ka' ? 'ka' : 'en';
        if ($locale === 'ka') {
            array_shift($parts);
        }
        $none = ['type' => null, 'id' => null, 'locale' => $locale];
        if (count($parts) !== 2) {
            return $none;
        }
        [$segment, $slug] = $parts;
        $slug = UrlRedirectService::find($segment, $slug) ?? $slug;
        $id   = $this->slugs()[$segment][$slug] ?? null;
        if (! $id) {
            return $none;
        }
        return ['type' => $this->typeOfSegment($segment), 'id' => $id, 'locale' => $locale];
    }

    /**
     * @param  array<int, array{0:string,1:int}>  $records  [type, id]
     * @return array<string, array>  "type:id" → [type, id, title, url, edit_route]
     */
    public function describe(array $records): array
    {
        $byType = [];
        foreach ($records as [$type, $id]) {
            if ($type && $id) {
                $byType[$type][] = (int) $id;
            }
        }
        $out = [];
        foreach ($byType as $type => $ids) {
            foreach ($this->rows($type, array_unique($ids)) as $row) {
                $out["{$type}:{$row->id}"] = [
                    'type'       => $type,
                    'id'         => (int) $row->id,
                    'category'   => $row->category ?? null,
                    'title'      => $row->title ?: $row->url_title,
                    'url'        => $this->publicUrl($type, $row->url_title, $row->category ?? null),
                    'edit_route' => $type === 'article' ? 'articleEdit' : (self::TYPES[$type][3] ?? null),
                    'published'  => isset($row->published) ? (int) $row->published : 1,
                ];
            }
        }
        return $out;
    }

    public function publicUrl(string $type, ?string $slug, ?string $category = null, string $locale = 'en'): ?string
    {
        if ($type === 'article') {
            $segment = self::ARTICLE_PATHS[$category] ?? null;
            $host    = config('app.site_url');
        } else {
            $segment = self::TYPES[$type][1] ?? null;
            $host    = config('app.' . (self::TYPES[$type][2] ?? 'site_url'));
        }
        if (! $segment || ! $slug) {
            return null;
        }
        $scheme = parse_url((string) config('app.url'), PHP_URL_SCHEME) === 'http' ? 'http' : 'https';
        return "{$scheme}://{$host}" . ($locale === 'ka' ? '/ka' : '') . "/{$segment}/" . rawurlencode($slug);
    }

    private function typeOfSegment(string $segment): string
    {
        if (in_array($segment, self::ARTICLE_PATHS, true)) {
            return 'article';
        }
        foreach (self::TYPES as $type => $def) {
            if ($def[1] === $segment) {
                return $type;
            }
        }
        return $segment;
    }

    /** segment → [url_title => id], all content types, loaded once */
    private function slugs(): array
    {
        if ($this->slugs !== null) {
            return $this->slugs;
        }
        $map = [];
        foreach (DB::table('articles')->get(['id', 'category', 'url_title']) as $a) {
            if (isset(self::ARTICLE_PATHS[$a->category]) && $a->url_title !== null) {
                $map[self::ARTICLE_PATHS[$a->category]][$a->url_title] = $a->id;
            }
        }
        foreach (self::TYPES as $type => [$table, $segment]) {
            if (! \Schema::hasTable($table)) {
                continue;
            }
            foreach (DB::table($table)->whereNotNull('url_title')->get(['id', 'url_title']) as $r) {
                $map[$segment][$r->url_title] = $r->id;
            }
        }
        return $this->slugs = $map;
    }

    /** id, url_title, published, English title (+ category for articles) */
    private function rows(string $type, array $ids)
    {
        if ($type === 'article') {
            return DB::table('articles as g')->leftJoin('locale_articles as l', 'l.id', '=', 'g.us_article_id')
                ->whereIn('g.id', $ids)->get(['g.id', 'g.url_title', 'g.category', 'g.published', 'l.title']);
        }
        if ($type === 'summit') {
            return DB::table('summits')->whereIn('id', $ids)->get(['id', 'url_title', 'published', 'title']);
        }
        $locale = [
            'event' => ['locale_events', 'us_event_id'], 'product' => ['locale_products', 'us_product_id'],
            'service' => ['locale_services', 'us_service_id'], 'tour' => ['locale_tours', 'us_tour_id'],
            'post' => ['locale_posts', 'us_post_id'], 'local_bisnes' => ['locale_bisneses', 'us_bisnes_id'],
            'film' => ['locale_films', 'us_film_id'],
        ][$type] ?? null;
        $table = self::TYPES[$type][0] ?? null;
        if (! $locale || ! $table || ! \Schema::hasTable($table)) {
            return [];
        }
        $titleCol = $type === 'film' ? 'name' : 'title';
        $q = DB::table("{$table} as g")->leftJoin("{$locale[0]} as l", 'l.id', '=', "g.{$locale[1]}")->whereIn('g.id', $ids);
        $cols = ['g.id', 'g.url_title', "l.{$titleCol} as title"];
        if (\Schema::hasColumn($table, 'published')) {
            $cols[] = 'g.published';
        }
        return $q->get($cols);
    }
}

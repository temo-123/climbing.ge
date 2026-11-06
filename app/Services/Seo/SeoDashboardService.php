<?php

namespace App\Services\Seo;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Admin SEO dashboard data (Api\User\Admin\User\SeoDashboardController):
 * overview, page opportunities, a page's queries, and the content quality list.
 */
class SeoDashboardService
{
    /** Typical CTR by position (organic, rough industry curve) — used to spot weak titles. */
    private const EXPECTED_CTR = [1 => .28, 2 => .15, 3 => .10, 4 => .07, 5 => .05, 6 => .04, 7 => .03, 8 => .025, 9 => .02, 10 => .02];

    public function __construct(private ContentResolver $resolver)
    {
    }

    /** [current from, current to, previous from, previous to] — windows end at the newest day with data */
    public function windows(int $days): ?array
    {
        $latest = DB::table('search_console_pages')->max('date');
        if (! $latest) {
            return null;
        }
        $to   = Carbon::parse($latest);
        $from = $to->copy()->subDays($days - 1);
        return [$from->toDateString(), $to->toDateString(), $from->copy()->subDays($days)->toDateString(), $from->copy()->subDay()->toDateString()];
    }

    public function overview(int $days): array
    {
        $w = $this->windows($days);
        if (! $w) {
            return ['has_data' => false];
        }
        $totals = fn ($from, $to) => (array) DB::table('search_console_pages')->whereBetween('date', [$from, $to])
            ->selectRaw('COALESCE(SUM(clicks),0) clicks, COALESCE(SUM(impressions),0) impressions, COALESCE(SUM(position*impressions)/NULLIF(SUM(impressions),0),0) position')
            ->first();
        $cur  = $totals($w[0], $w[1]);
        $prev = $totals($w[2], $w[3]);
        foreach ([&$cur, &$prev] as &$t) {
            $t = ['clicks' => (int) $t['clicks'], 'impressions' => (int) $t['impressions'], 'position' => round((float) $t['position'], 1),
                  'ctr' => $t['impressions'] ? round($t['clicks'] / $t['impressions'] * 100, 2) : 0];
        }
        unset($t);

        $services = DB::table('search_console_pages')->whereBetween('date', [$w[0], $w[1]])
            ->groupBy('host')->orderByDesc(DB::raw('SUM(clicks)'))
            ->selectRaw('host, SUM(clicks) clicks, SUM(impressions) impressions, SUM(position*impressions)/NULLIF(SUM(impressions),0) position')
            ->get()->map(fn ($r) => ['host' => $r->host, 'clicks' => (int) $r->clicks, 'impressions' => (int) $r->impressions, 'position' => round((float) $r->position, 1)]);

        $languages = DB::table('search_console_pages')->whereBetween('date', [$w[0], $w[1]])
            ->groupBy('locale')->selectRaw('locale, SUM(clicks) clicks, SUM(impressions) impressions')->get()
            ->mapWithKeys(fn ($r) => [$r->locale => ['clicks' => (int) $r->clicks, 'impressions' => (int) $r->impressions]]);

        $daily = DB::table('search_console_pages')->whereBetween('date', [$w[0], $w[1]])
            ->groupBy('date')->orderBy('date')->selectRaw('date, SUM(clicks) clicks, SUM(impressions) impressions')->get()
            ->map(fn ($r) => ['date' => (string) $r->date, 'clicks' => (int) $r->clicks, 'impressions' => (int) $r->impressions]);

        return ['has_data' => true, 'window' => $w, 'current' => $cur, 'previous' => $prev,
                'services' => $services, 'languages' => $languages, 'daily' => $daily];
    }

    /**
     * Pages with metrics for the period, the previous period, and flags.
     * filter: all | losing | striking | low_ctr; host: optional service host.
     */
    public function pages(int $days, string $filter = 'all', ?string $host = null, int $limit = 300): array
    {
        $w = $this->windows($days);
        if (! $w) {
            return [];
        }
        $agg = fn ($from, $to) => DB::table('search_console_pages')->whereBetween('date', [$from, $to])
            ->when($host, fn ($q) => $q->where('host', $host))
            ->groupBy('url_hash')
            ->selectRaw('url_hash, MAX(url) url, MAX(locale) locale, MAX(content_type) content_type, MAX(content_id) content_id,
                         SUM(clicks) clicks, SUM(impressions) impressions, SUM(position*impressions)/NULLIF(SUM(impressions),0) position');
        $prev = $agg($w[2], $w[3])->get()->keyBy('url_hash');

        $minImpressions = max(20, $days * 2);
        $rows = [];
        foreach ($agg($w[0], $w[1])->get() as $r) {
            $p = $prev[$r->url_hash] ?? null;
            $impr = (int) $r->impressions;
            $pos  = round((float) $r->position, 1);
            $ctr  = $impr ? $r->clicks / $impr : 0;
            $prevClicks = (int) ($p->clicks ?? 0);
            $expected = self::EXPECTED_CTR[(int) max(1, round($pos))] ?? .01;
            $flags = [];
            if ($prevClicks >= 10 && $r->clicks <= $prevClicks * 0.7) $flags[] = 'losing';
            if ($pos >= 4.5 && $pos <= 20 && $impr >= $minImpressions) $flags[] = 'striking';
            if ($impr >= max(100, $minImpressions) && $pos <= 10 && $ctr < $expected * 0.5) $flags[] = 'low_ctr';
            if ($filter !== 'all' && ! in_array($filter, $flags, true)) {
                continue;
            }
            $rows[] = [
                'url_hash' => $r->url_hash, 'url' => $r->url, 'locale' => $r->locale,
                'content_type' => $r->content_type, 'content_id' => $r->content_id ? (int) $r->content_id : null,
                'clicks' => (int) $r->clicks, 'impressions' => $impr, 'ctr' => round($ctr * 100, 2), 'position' => $pos,
                'prev_clicks' => $prevClicks, 'prev_impressions' => (int) ($p->impressions ?? 0),
                'prev_position' => $p ? round((float) $p->position, 1) : null,
                'expected_ctr' => round($expected * 100, 1), 'flags' => $flags,
            ];
        }
        usort($rows, match ($filter) {
            'losing' => fn ($a, $b) => ($a['clicks'] - $a['prev_clicks']) <=> ($b['clicks'] - $b['prev_clicks']),
            'striking', 'low_ctr' => fn ($a, $b) => $b['impressions'] <=> $a['impressions'],
            default => fn ($a, $b) => [$b['clicks'], $b['impressions']] <=> [$a['clicks'], $a['impressions']],
        });
        $rows = array_slice($rows, 0, $limit);

        $content = $this->resolver->describe(array_map(fn ($r) => [$r['content_type'], $r['content_id']], $rows));
        foreach ($rows as &$r) {
            $r['content'] = $content["{$r['content_type']}:{$r['content_id']}"] ?? null;
        }
        return $rows;
    }

    public function pageQueries(string $urlHash, int $limit = 50): array
    {
        return DB::table('search_console_queries')->where('url_hash', $urlHash)
            ->orderByDesc('impressions')->limit($limit)->get(['query', 'clicks', 'impressions', 'position'])
            ->map(fn ($q) => ['query' => $q->query, 'clicks' => (int) $q->clicks, 'impressions' => (int) $q->impressions,
                              'ctr' => $q->impressions ? round($q->clicks / $q->impressions * 100, 2) : 0, 'position' => round((float) $q->position, 1)])
            ->all();
    }

    // ── Content quality ─────────────────────────────────────────────────────────

    /** issue code → weight (how much it hurts search; the list sorts by traffic, then this) */
    public const ISSUES = [
        'missing_translation' => 5, 'placeholder_summary' => 3, 'thin_text' => 3, 'not_georgian' => 2,
        'no_image' => 2, 'short_summary' => 1, 'caps_title' => 1, 'long_title' => 1,
    ];

    /** type → [global table, locale table, us fk, ka fk, text columns, min words, image column|null] */
    private const QUALITY_TYPES = [
        'article' => ['articles', 'locale_articles', 'us_article_id', 'ka_article_id', ['text', 'route', 'how_get', 'best_time', 'what_need', 'info', 'price_text'], 100, 'image'],
        'event'   => ['events', 'locale_events', 'us_event_id', 'ka_event_id', ['text', 'info'], 60, 'image'],
        'post'    => ['posts', 'locale_posts', 'us_post_id', 'ka_post_id', ['text'], 150, 'image'],
        'product' => ['products', 'locale_products', 'us_product_id', 'ka_product_id', ['text', 'material'], 50, null],
        'service' => ['services', 'locale_services', 'us_service_id', 'ka_service_id', ['text'], 50, null],
        'tour'    => ['tours', 'locale_tours', 'us_tour_id', 'ka_tour_id', ['text', 'duration', 'location'], 50, null],
    ];

    /** Published content with its problems, most-seen first. Cached 10 min (cleared by a sync). */
    public function contentQuality(): array
    {
        return Cache::remember('seo_dashboard:content_quality', 600, function () {
            $w = $this->windows(28);
            $traffic = $w ? DB::table('search_console_pages')->whereBetween('date', [$w[0], $w[1]])->whereNotNull('content_type')
                ->groupBy('content_type', 'content_id')->selectRaw('content_type, content_id, SUM(clicks) clicks, SUM(impressions) impressions')
                ->get()->keyBy(fn ($r) => "{$r->content_type}:{$r->content_id}") : collect();

            $rows = [];
            foreach (self::QUALITY_TYPES as $type => [$table, $localeTable, $usFk, $kaFk, $textCols, $minWords, $imageCol]) {
                if (! \Schema::hasTable($table)) {
                    continue;
                }
                $globals = DB::table($table)->where('published', 1)->get();
                $locales = DB::table($localeTable)->whereIn('id', $globals->pluck($usFk)->merge($globals->pluck($kaFk))->filter())->get()->keyBy('id');
                foreach ($globals as $g) {
                    $issues = [];
                    foreach (['en' => $usFk, 'ka' => $kaFk] as $lang => $fk) {
                        $l = $locales[$g->$fk] ?? null;
                        // a title needs one real word ("Handboard"); summaries need two (see meaningful())
                        if (! $l || ! preg_match('/\p{L}{2,}/u', $this->plain($l->title ?? ''))) {
                            $issues[] = ['code' => 'missing_translation', 'locale' => $lang];
                            continue;
                        }
                        $title   = $this->plain($l->title);
                        $summary = $this->plain($l->short_description ?? '');
                        $words   = $this->words(implode(' ', array_map(fn ($c) => $l->$c ?? '', $textCols)));
                        if (! $this->meaningful($summary))      $issues[] = ['code' => 'placeholder_summary', 'locale' => $lang];
                        elseif (mb_strlen($summary) < 70)        $issues[] = ['code' => 'short_summary', 'locale' => $lang, 'value' => mb_strlen($summary)];
                        if ($words < $minWords)                  $issues[] = ['code' => 'thin_text', 'locale' => $lang, 'value' => $words, 'min' => $minWords];
                        if ($this->allCaps($title))              $issues[] = ['code' => 'caps_title', 'locale' => $lang];
                        if (mb_strlen($title) > 60)              $issues[] = ['code' => 'long_title', 'locale' => $lang, 'value' => mb_strlen($title)];
                        if ($lang === 'ka' && ! preg_match('/[\x{10A0}-\x{10FF}]/u', $title . ' ' . $summary))
                                                                 $issues[] = ['code' => 'not_georgian', 'locale' => 'ka'];
                    }
                    if ($imageCol && empty($g->$imageCol)) {
                        $issues[] = ['code' => 'no_image', 'locale' => null];
                    }
                    if (! $issues) {
                        continue;
                    }
                    $t = $traffic["{$type}:{$g->id}"] ?? null;
                    $rows[] = [
                        'type' => $type, 'id' => (int) $g->id, 'category' => $g->category ?? null,
                        'issues' => $issues,
                        'score' => array_sum(array_map(fn ($i) => self::ISSUES[$i['code']], $issues)),
                        'impressions' => (int) ($t->impressions ?? 0), 'clicks' => (int) ($t->clicks ?? 0),
                    ];
                }
            }
            usort($rows, fn ($a, $b) => [$b['impressions'], $b['score']] <=> [$a['impressions'], $a['score']]);

            $content = $this->resolver->describe(array_map(fn ($r) => [$r['type'], $r['id']], $rows));
            foreach ($rows as &$r) {
                $r['content'] = $content["{$r['type']}:{$r['id']}"] ?? null;
            }
            unset($r);

            $summary = [];
            foreach ($rows as $r) {
                foreach (array_unique(array_column($r['issues'], 'code')) as $code) {
                    $summary[$code] = ($summary[$code] ?? 0) + 1;
                }
            }
            return ['rows' => $rows, 'summary' => $summary, 'has_traffic' => (bool) $w];
        });
    }

    private function plain(?string $html): string
    {
        $text = html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text));
    }

    private function meaningful(?string $text): bool
    {
        return preg_match_all('/\p{L}{2,}/u', $this->plain($text)) >= 2;
    }

    private function words(string $html): int
    {
        return preg_match_all('/\p{L}[\p{L}\p{M}\'’-]*/u', $this->plain($html));
    }

    private function allCaps(string $title): bool
    {
        $letters = preg_replace('/[^A-Za-z]/', '', $title);
        return strlen($letters) >= 8 && strtoupper($letters) === $letters;
    }
}

<?php

namespace App\Services;

use App\Mail\SeasonalCampaignMessage;
use App\Mail\UserNotificationMessage;
use App\Models\Guide\Article;
use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Sector;
use App\Models\User;
use App\Models\User\ContentNotificationLog;
use App\Models\User\SeasonalCampaign;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Seasonal campaigns: during a campaign's season window, each eligible user gets a mail with
 * 1–3 articles / sectors picked for them (never one they already got from this campaign, never
 * one they favorited), at most every `every_days` days, and never two seasonal mails within
 * GLOBAL_GAP_DAYS of each other. Docs: docs/FUNCTIONS_DOCS/user/seasonal_campaigns.md
 */
class SeasonalCampaignService
{
    /** article categories a campaign can feature */
    public const CATEGORIES = ['outdoor', 'ice', 'mount_route', 'indoor', 'tech_tip', 'news', 'other'];
    public const CONTENT_TYPES = ['article', 'sector'];
    public const PICKS = ['popular', 'random', 'newest'];
    public const AUDIENCES = ['interested', 'all', 'inactive'];
    public const SUN_FILTERS = ['any', 'shade', 'sun'];

    /** no user gets two seasonal mails (any campaign) closer than this */
    public const GLOBAL_GAP_DAYS = 3;

    /** "popular" = a random pick among this many most popular items, so mails don't all look alike */
    private const POPULAR_POOL = 10;

    /** audience "interested": users who asked for new content of this category */
    private const PREFERENCE_KEYS = [
        'outdoor'     => 'add_new_outdoor_spot',
        'ice'         => 'add_new_ice_spot',
        'indoor'      => 'add_new_gym',
        'tech_tip'    => 'add_new_techtip',
        'news'        => 'news',
        'mount_route' => 'add_new_mount',
        'other'       => 'add_new_outdoor_spot',
    ];

    // ── season ────────────────────────────────────────────────────────────────

    public static function in_season(SeasonalCampaign $c, ?Carbon $day = null): bool
    {
        $md = ($day ?? self::today())->format('m-d');
        return $c->start_md <= $c->end_md
            ? ($md >= $c->start_md && $md <= $c->end_md)
            : ($md >= $c->start_md || $md <= $c->end_md);   // wraps the new year (Dec → Feb)
    }

    /** next day the season starts (null while in season) */
    public static function next_start(SeasonalCampaign $c, ?Carbon $day = null): ?Carbon
    {
        $day = ($day ?? self::today())->copy()->startOfDay();
        if (self::in_season($c, $day)) return null;
        $start = self::md_date($c->start_md, $day->year);
        return $start->lt($day) ? self::md_date($c->start_md, $day->year + 1) : $start;
    }

    /** last day of the current season (null when out of season) */
    public static function season_end(SeasonalCampaign $c, ?Carbon $day = null): ?Carbon
    {
        $day = ($day ?? self::today())->copy()->startOfDay();
        if (!self::in_season($c, $day)) return null;
        $end = self::md_date($c->end_md, $day->year);
        return $end->lt($day) ? self::md_date($c->end_md, $day->year + 1) : $end;
    }

    private static function md_date(string $md, int $year): Carbon
    {
        [$m, $d] = array_map('intval', explode('-', $md));
        // 02-29 in a non-leap year → 02-28
        return Carbon::create($year, $m, min($d, Carbon::create($year, $m, 1)->daysInMonth))->startOfDay();
    }

    private static function today(): Carbon
    {
        return Carbon::now(config('app.timezone'));
    }

    // ── content ───────────────────────────────────────────────────────────────

    /**
     * Everything the campaign could feature right now, as plain arrays:
     * type, id, article_id, title/text [us, ka], url, image, popularity, created_at.
     */
    public static function candidates(SeasonalCampaign $c): Collection
    {
        return $c->content_type === 'sector' ? self::sector_candidates($c) : self::article_candidates($c);
    }

    private static function article_candidates(SeasonalCampaign $c): Collection
    {
        return Article::query()
            ->where('category', $c->category)
            ->where('published', 1)
            ->with(['global_article_us:id,title,short_description', 'global_article_ka:id,title,short_description'])
            ->withCount('comments')
            ->addSelect(['favorites_count' => Favorite_outdoor_area::selectRaw('count(*)')->whereColumn('article_id', 'articles.id')])
            ->get()
            ->map(function (Article $a) {
                $us = $a->global_article_us;
                $ka = $a->global_article_ka;
                return [
                    'type'       => Article::class,
                    'id'         => $a->id,
                    'article_id' => $a->id,
                    'title'      => ['us' => $us->title ?? '', 'ka' => $ka->title ?? ''],
                    'text'       => ['us' => self::short($us->short_description ?? ''), 'ka' => self::short($ka->short_description ?? '')],
                    'url'        => self::article_url($a),
                    'image'      => self::article_image($a),
                    'popularity' => 3 * (int) $a->favorites_count + (int) $a->comments_count,
                    'created_at' => (string) $a->created_at,
                ];
            })
            ->filter(fn ($i) => $i['title']['us'] !== '' || $i['title']['ka'] !== '')
            ->values();
    }

    private static function sector_candidates(SeasonalCampaign $c): Collection
    {
        $q = Sector::query()
            ->where('published', 1)
            ->whereHas('article', fn ($a) => $a->where('category', $c->category)->where('published', 1))
            ->with(['article' => fn ($a) => $a->with(['global_article_us:id,title,short_description', 'global_article_ka:id,title,short_description'])])
            ->withCount('routes');

        if ($c->sun_filter === 'shade') {
            $q->where(fn ($w) => $w->where('all_day_in_shade', 1)->orWhere('in_the_shade_afternoon', 1)
                ->orWhere('in_shade_after_15', 1));
        } elseif ($c->sun_filter === 'sun') {
            $q->where('all_day_in_sun', 1);
        }

        return $q->get()
            // skip placeholder sectors ("-", "?") and ones without routes
            ->filter(fn (Sector $s) => $s->routes_count > 0 && $s->article && preg_match('/[\p{L}\p{N}]/u', (string) $s->name))
            ->map(function (Sector $s) {
                $a = $s->article;
                $area = ['us' => $a->global_article_us->title ?? '', 'ka' => $a->global_article_ka->title ?? ''];
                $name = trim((string) $s->name);
                return [
                    'type'       => Sector::class,
                    'id'         => $s->id,
                    'article_id' => $a->id,
                    'title'      => [
                        'us' => $area['us'] !== '' ? $name . ' — ' . $area['us'] : $name,
                        'ka' => $area['ka'] !== '' ? $name . ' — ' . $area['ka'] : '',
                    ],
                    // facts first ("14 routes · 5 min walk · shade all day"), then the sector's own description
                    'text'       => [
                        'us' => trim(self::sector_facts($s, 'us') . ' ' . self::short((string) $s->us_description)),
                        'ka' => trim(self::sector_facts($s, 'ka') . ' ' . self::short((string) $s->ka_description)),
                    ],
                    'url'        => self::article_url($a) . '?sector=' . $s->id,
                    'image'      => self::article_image($a),
                    'popularity' => (int) $s->routes_count,
                    'created_at' => (string) $s->created_at,
                    'routes'     => (int) $s->routes_count,
                ];
            })
            ->values();
    }

    private static function sector_facts(Sector $s, string $locale): string
    {
        $ka = $locale === 'ka';
        $facts = [$ka ? $s->routes_count . ' მარშრუტი' : $s->routes_count . ' route' . ($s->routes_count === 1 ? '' : 's')];
        $walk = trim((string) $s->wolking_time);
        if ($walk !== '') $facts[] = $ka ? $walk . ' წთ სავალი' : $walk . ' min walk';
        if ($s->all_day_in_shade) $facts[] = $ka ? 'მთელი დღე ჩრდილში' : 'shade all day';
        elseif ($s->in_the_shade_afternoon || $s->in_shade_after_15) $facts[] = $ka ? 'შუადღით ჩრდილში' : 'shade in the afternoon';
        elseif ($s->in_the_shade_befornoon) $facts[] = $ka ? 'დილით ჩრდილში' : 'shade in the morning';
        elseif ($s->all_day_in_sun) $facts[] = $ka ? 'მთელი დღე მზეზე' : 'sun all day';
        if ($s->for_family || $s->for_kids) $facts[] = $ka ? 'ოჯახისთვის' : 'family friendly';
        return implode(' · ', $facts) . '.';
    }

    private static function article_url(Article $a): string
    {
        $segment = $a->slugSegments()[0] ?? 'outdoor';
        return rtrim((string) config('app.base_url_ssh'), '/') . '/' . $segment . '/' . $a->url_title;
    }

    private static function article_image(Article $a): ?string
    {
        if (!$a->image) return null;
        return rtrim((string) config('app.url'), '/') . '/images/' . $a->category . '_img/' . rawurlencode($a->image);
    }

    private static function short(?string $text): string
    {
        $plain = html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return Str::limit(trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $plain)), 180);
    }

    // ── audience ──────────────────────────────────────────────────────────────

    /** users the campaign may write to (before per-user throttles) */
    public static function audience_query(SeasonalCampaign $c): Builder
    {
        $q = User::query()
            ->whereNotNull('email_verified_at')
            ->whereDoesntHave('role', fn ($r) => $r->where('slug', 'ban'))
            // seasonal_picks missing = on; only an explicit false opts out
            ->whereHas('notification_list', function ($n) use ($c) {
                $n->where(fn ($w) => $w->whereNull('notification_type->seasonal_picks')
                    ->orWhere('notification_type->seasonal_picks', true));
                // master switch: all notification mail off
                $n->where(fn ($w) => $w->whereNull('notification_type->all_off')
                    ->orWhere('notification_type->all_off', false));
                if ($c->audience === 'interested') {
                    $keys = array_unique(array_filter([
                        self::PREFERENCE_KEYS[$c->category] ?? null,
                        $c->content_type === 'sector' ? 'add_new_sector' : null,
                    ]));
                    $n->where(function ($w) use ($keys) {
                        foreach ($keys as $k) $w->orWhere('notification_type->' . $k, true);
                    });
                }
            });

        if ($c->audience === 'inactive') {
            $cutoff = now()->subDays(max(1, (int) ($c->inactive_days ?: 30)));
            $q->where(fn ($w) => $w->where('last_seen_at', '<', $cutoff)
                ->orWhere(fn ($n) => $n->whereNull('last_seen_at')->where('created_at', '<', $cutoff)));
        }

        return $q;
    }

    public static function audience_count(SeasonalCampaign $c): int
    {
        return self::audience_query($c)->count();
    }

    // ── per user ──────────────────────────────────────────────────────────────

    /** throttles: this campaign's every_days, and the global gap between any two seasonal mails */
    public static function due_for(SeasonalCampaign $c, User $user): bool
    {
        $recent = ContentNotificationLog::where('user_id', $user->id)
            ->where('notification_key', $c->logKey())
            ->where('sent_at', '>', now()->subDays(max(1, $c->every_days)))
            ->exists();
        if ($recent) return false;

        return !ContentNotificationLog::where('user_id', $user->id)
            ->where('notification_key', 'like', 'seasonal\_%')
            ->where('sent_at', '>', now()->subDays(self::GLOBAL_GAP_DAYS))
            ->exists();
    }

    /** the items this user would get now: not sent before by this campaign, not favorited */
    public static function pick_for(SeasonalCampaign $c, User $user, Collection $candidates): array
    {
        if ($candidates->isEmpty()) return [];

        $type = $c->content_type === 'sector' ? Sector::class : Article::class;
        $sent = ContentNotificationLog::where('user_id', $user->id)
            ->where('notification_key', $c->logKey())
            ->where('notifiable_type', $type)
            ->pluck('notifiable_id')->flip();
        $favorites = Favorite_outdoor_area::where('user_id', $user->id)->pluck('article_id')->flip();
        $locale = $user->lang === 'ka' ? 'ka' : 'us';

        $pool = $candidates
            ->reject(fn ($i) => isset($sent[$i['id']]))
            ->reject(fn ($i) => $type === Article::class && isset($favorites[$i['article_id']]))
            ->filter(fn ($i) => $i['title'][$locale] !== '' || $i['title']['us'] !== '');

        $n = max(1, min(3, $c->items_per_mail));
        $picked = match ($c->pick) {
            'newest' => $pool->sortByDesc('created_at')->take($n),
            'random' => $pool->shuffle()->take($n),
            default  => $pool->sortByDesc('popularity')->take(max(self::POPULAR_POOL, $n))->shuffle()->take($n),
        };

        return $picked->values()->map(fn ($i) => [
            'type'  => $i['type'],
            'id'    => $i['id'],
            'title' => $i['title'][$locale] !== '' ? $i['title'][$locale] : $i['title']['us'],
            'text'  => $i['text'][$locale] !== '' ? $i['text'][$locale] : $i['text']['us'],
            'url'   => self::with_utm($i['url'], $c),
            'image' => $i['image'],
        ])->all();
    }

    /** GA: clicks show up under Acquisition → campaign "seasonal_<id>" */
    public static function with_utm(string $url, SeasonalCampaign $c): string
    {
        return $url . (str_contains($url, '?') ? '&' : '?') . http_build_query([
            'utm_source'   => 'climbing_email',
            'utm_medium'   => 'email',
            'utm_campaign' => 'seasonal_' . $c->id,
        ]);
    }

    // ── sending ───────────────────────────────────────────────────────────────

    /**
     * One daily run of one campaign. Returns counters for the command / dashboard.
     * $dry = count only, send nothing.
     */
    public static function run(SeasonalCampaign $c, bool $dry = false): array
    {
        $result = ['campaign' => $c->id, 'sent' => 0, 'throttled' => 0, 'nothing_left' => 0, 'checked' => 0, 'skipped' => null];

        if (!self::in_season($c)) {
            $result['skipped'] = 'out_of_season';
            return $result;
        }
        $candidates = self::candidates($c);
        if ($candidates->isEmpty()) {
            $result['skipped'] = 'no_content';
            return $result;
        }

        $limit = max(1, $c->max_per_run);
        foreach (self::audience_query($c)->orderBy('id')->lazy(200) as $user) {
            if ($result['sent'] >= $limit) break;
            $result['checked']++;
            if ($user->isUnderage() || !self::due_for($c, $user)) {
                $result['throttled']++;
                continue;
            }
            $items = self::pick_for($c, $user, $candidates);
            if (!$items) {
                $result['nothing_left']++;
                continue;
            }
            if ($dry || self::send($c, $user, $items)) $result['sent']++;
        }

        if (!$dry) {
            $c->forceFill(['last_run_at' => now(), 'last_run_sent' => $result['sent']])->save();
        }
        return $result;
    }

    /** logs every item (dedup) and queues the mail; false when every item was already logged */
    public static function send(SeasonalCampaign $c, User $user, array $items, bool $log = true): bool
    {
        if ($log) {
            $now = now();
            $inserted = ContentNotificationLog::insertOrIgnore(array_map(fn ($i) => [
                'user_id'          => $user->id,
                'notifiable_type'  => $i['type'],
                'notifiable_id'    => $i['id'],
                'notification_key' => $c->logKey(),
                'sent_at'          => $now,
                'created_at'       => $now,
                'updated_at'       => $now,
            ], $items));
            if ($inserted === 0) return false;
        }

        $locale = $user->lang === 'ka' ? 'ka' : 'us';
        Mail::to($user->email)->queue(new SeasonalCampaignMessage([
            'locale'     => $locale,
            'subject'    => ($locale === 'ka' && $c->subject_ka) ? $c->subject_ka : $c->subject_us,
            'intro'      => ($locale === 'ka' && $c->intro_ka) ? $c->intro_ka : (string) $c->intro_us,
            'name'       => trim((string) $user->name),
            'items'      => $items,
            'manage_url' => UserNotificationMessage::manage_alerts_url(),
            'unsubscribe_url' => NotificationPreferenceService::unsubscribe_url($user),
        ]));
        return true;
    }

    // ── dashboard ─────────────────────────────────────────────────────────────

    public static function stats(SeasonalCampaign $c): array
    {
        $base = fn () => DB::table('content_notification_logs')->where('notification_key', $c->logKey());
        // one mail = one (user, sent_at) pair; each item in it is its own log row
        $mails = fn ($q) => (int) $q->selectRaw('count(distinct user_id, sent_at) as n')->value('n');

        return [
            'mails_total' => $mails($base()),
            'mails_30d'   => $mails($base()->where('sent_at', '>', now()->subDays(30))),
            'users_total' => (int) $base()->distinct()->count('user_id'),
            'items_total' => (int) $base()->count(),
        ];
    }
}

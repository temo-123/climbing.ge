<?php

namespace App\Services;

use App\Models\Guide\Article;
use App\Models\Guide\ArticleWorkingHour;
use Illuminate\Support\Facades\DB;

/**
 * Indoor gym opening hours per weekday (table article_working_hours).
 * The frontend reads them as { mon: { open, close } | { closed: true }, … sun }
 * (resources/js/services/guide/gymHours.js); no rows = same hours every day
 * (articles.open_time / closed_time).
 */
class GymHoursService
{
    const DAYS = [1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat', 7 => 'sun'];

    // rows → the frontend's week object (null when the gym has no per-day hours)
    public static function week_from_rows($rows): ?array
    {
        if (!$rows || count($rows) === 0) return null;
        $week = [];
        foreach (self::DAYS as $n => $key) $week[$key] = ['closed' => true];
        foreach ($rows as $row) {
            $key = self::DAYS[(int) $row->weekday] ?? null;
            if (!$key) continue;
            $week[$key] = (!$row->is_closed && $row->opens_at && $row->closes_at)
                ? ['open' => substr($row->opens_at, 0, 5), 'close' => substr($row->closes_at, 0, 5)]
                : ['closed' => true];
        }
        return $week;
    }

    public static function week_for(int $article_id): ?array
    {
        return self::week_from_rows(ArticleWorkingHour::where('article_id', $article_id)->orderBy('weekday')->get());
    }

    /** every published indoor gym that has per-day hours: article id → week */
    public static function all_indoor_weeks(): array
    {
        $rows = ArticleWorkingHour::whereIn('article_id', Article::where('category', 'indoor')->whereIn('published', [1, 2])->pluck('id'))
            ->orderBy('weekday')->get()->groupBy('article_id');
        $out = [];
        foreach ($rows as $article_id => $list) $out[$article_id] = self::week_from_rows($list);
        return $out;
    }

    /**
     * Save a gym's week. $days = 7 × { weekday 1–7, open "HH:MM", close "HH:MM", closed bool };
     * an empty list removes the per-day hours. A day without both times counts
     * as closed. open_time / closed_time get the week's most common hours, so
     * "same every day" stays a sensible summary.
     */
    public static function save(Article $article, array $days): ?array
    {
        $valid = fn ($t) => is_string($t) && preg_match('/^([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?$/', $t);

        return DB::transaction(function () use ($article, $days, $valid) {
            ArticleWorkingHour::where('article_id', $article->id)->delete();
            if (!$days) return null;

            $counts = [];
            foreach ($days as $d) {
                $n = (int) ($d['weekday'] ?? 0);
                if (!isset(self::DAYS[$n])) continue;
                $open = $d['open'] ?? null;
                $close = $d['close'] ?? null;
                $closed = !empty($d['closed']) || !$valid($open) || !$valid($close);
                ArticleWorkingHour::create([
                    'article_id' => $article->id,
                    'weekday'    => $n,
                    'opens_at'   => $closed ? null : substr($open, 0, 5),
                    'closes_at'  => $closed ? null : substr($close, 0, 5),
                    'is_closed'  => $closed,
                ]);
                if (!$closed) {
                    $key = substr($open, 0, 5) . '-' . substr($close, 0, 5);
                    $counts[$key] = ($counts[$key] ?? 0) + 1;
                }
            }

            if ($counts) {
                arsort($counts);
                [$o, $c] = explode('-', array_key_first($counts));
                $article->update(['open_time' => $o, 'closed_time' => $c]);
            }
            return self::week_for($article->id);
        });
    }
}

<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Shop\Tour;
use App\Models\Shop\Tour_season;

/**
 * Tour season + duration schedule — docs/FUNCTIONS_DOCS/shop/tour_schedule.md
 *
 * - Season: all_year, or yearly periods (tour_seasons). Every day of the trip
 *   (check-in … check-in + days − 1) must be inside one of the periods.
 * - Durations: duration_mode NULL → the price list's durations (or 1–7 when there is
 *   none), 'fixed' → duration_days, 'range' → duration_min … duration_max.
 * - Start weekdays (start_weekdays, NULL = any) and booking_lead_days (book N days ahead).
 *
 * The shop calendar (TourDatePickerComponent + services/shop/tourSchedule.js) applies the
 * same rules; the server checks again when the reservation is saved.
 */
class TourScheduleService
{
    const MODES = ['fixed', 'range'];
    const MAX_DAYS = 60;

    public static function seasons($tour_id)
    {
        return Tour_season::where('tour_id', $tour_id)
            ->orderBy('from_month')->orderBy('from_day')
            ->get(['id', 'from_month', 'from_day', 'to_month', 'to_day']);
    }

    /** Durations the visitor can choose. */
    public static function durations(Tour $tour): array
    {
        if ($tour->duration_mode === 'fixed') {
            $list = array_values(array_unique(array_map('intval', (array) ($tour->duration_days ?: []))));
            $list = array_values(array_filter($list, fn ($d) => $d >= 1 && $d <= self::MAX_DAYS));
            sort($list);
            if ($list) return $list;
        }
        if ($tour->duration_mode === 'range' && $tour->duration_min) {
            $min = max(1, (int) $tour->duration_min);
            $max = min(self::MAX_DAYS, max($min, (int) ($tour->duration_max ?: $min)));
            return range($min, $max);
        }
        $from_prices = TourPriceService::rules($tour->id)->pluck('days')->unique()->sort()->values()->all();
        return $from_prices ?: range(1, 7);
    }

    /** Public payload for the reservation form. */
    public static function public_schedule(Tour $tour): array
    {
        return [
            'all_year' => (bool) $tour->all_year,
            'seasons' => (bool) $tour->all_year ? [] : self::seasons($tour->id)->values(),
            'durations' => self::durations($tour),
            'start_weekdays' => self::weekdays($tour),
            'lead_days' => (int) $tour->booking_lead_days,
        ];
    }

    /** Admin editor payload. */
    public static function admin_schedule(Tour $tour): array
    {
        return [
            'all_year' => (bool) $tour->all_year,
            'seasons' => self::seasons($tour->id)->values(),
            'duration_mode' => $tour->duration_mode,
            'duration_days' => array_values(array_map('intval', (array) ($tour->duration_days ?: []))),
            'duration_min' => $tour->duration_min,
            'duration_max' => $tour->duration_max,
            'start_weekdays' => self::weekdays($tour),
            'lead_days' => (int) $tour->booking_lead_days,
            'price_durations' => TourPriceService::rules($tour->id)->pluck('days')->unique()->sort()->values(),
        ];
    }

    private static function weekdays(Tour $tour): ?array
    {
        $list = $tour->start_weekdays;
        if (!is_array($list) || !count($list) || count($list) >= 7) return null;
        $list = array_values(array_unique(array_map('intval', $list)));
        sort($list);
        return $list;
    }

    /** Is one calendar day inside the season? */
    public static function in_season(Tour $tour, Carbon $day, $seasons = null): bool
    {
        if ($tour->all_year) return true;
        $seasons = $seasons ?? self::seasons($tour->id);
        if ($seasons->isEmpty()) return true; // not all year but no period set → don't block
        $md = $day->month * 100 + $day->day;
        foreach ($seasons as $s) {
            $from = $s->from_month * 100 + $s->from_day;
            $to = $s->to_month * 100 + $s->to_day;
            if ($from <= $to ? ($md >= $from && $md <= $to) : ($md >= $from || $md <= $to)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check a trip. Returns null when fine, else ['field' => 'check_in'|'days', 'message' => …].
     */
    public static function check_trip(Tour $tour, $check_in, int $days): ?array
    {
        if (!in_array($days, self::durations($tour), true)) {
            return ['field' => 'days', 'message' => 'This duration is not available for this tour.'];
        }

        try {
            $start = Carbon::parse($check_in)->startOfDay();
        } catch (\Throwable $e) {
            return ['field' => 'check_in', 'message' => 'Please enter a valid date.'];
        }

        $earliest = Carbon::today()->addDays((int) $tour->booking_lead_days);
        if ($start->lt($earliest)) {
            return ['field' => 'check_in', 'message' => $tour->booking_lead_days
                ? 'This tour must be booked at least ' . $tour->booking_lead_days . ' day(s) ahead.'
                : 'The check-in date must be today or in the future.'];
        }

        $weekdays = self::weekdays($tour);
        if ($weekdays !== null && !in_array($start->dayOfWeek, $weekdays, true)) {
            return ['field' => 'check_in', 'message' => 'The tour does not start on this day of the week.'];
        }

        $seasons = self::seasons($tour->id);
        for ($i = 0; $i < $days; $i++) {
            if (!self::in_season($tour, $start->copy()->addDays($i), $seasons)) {
                return ['field' => 'check_in', 'message' => $i === 0
                    ? 'The tour does not run on this date (out of season).'
                    : 'The trip would end out of season. Choose an earlier date or a shorter duration.'];
            }
        }

        return null;
    }

    /**
     * Save from the admin editor (validated by the controller).
     * @return string|null error
     */
    public static function save(Tour $tour, array $data): ?string
    {
        $all_year = !empty($data['all_year']);
        $seasons = [];
        foreach (($data['seasons'] ?? []) as $i => $s) {
            foreach (['from', 'to'] as $edge) {
                $m = (int) $s[$edge . '_month'];
                $d = (int) $s[$edge . '_day'];
                if (!checkdate($m, $d, 2024)) { // leap year → 29 Feb allowed
                    return 'Period ' . ($i + 1) . ': ' . $edge . ' date does not exist.';
                }
            }
            $seasons[] = [
                'from_month' => (int) $s['from_month'], 'from_day' => (int) $s['from_day'],
                'to_month' => (int) $s['to_month'], 'to_day' => (int) $s['to_day'],
            ];
        }
        if (!$all_year && !$seasons) {
            return 'Add at least one period, or choose "All year".';
        }

        $mode = in_array($data['duration_mode'] ?? null, self::MODES, true) ? $data['duration_mode'] : null;
        $days = array_values(array_unique(array_map('intval', $data['duration_days'] ?? [])));
        sort($days);
        $min = isset($data['duration_min']) && $data['duration_min'] !== '' ? (int) $data['duration_min'] : null;
        $max = isset($data['duration_max']) && $data['duration_max'] !== '' ? (int) $data['duration_max'] : null;
        if ($mode === 'fixed' && !$days) return 'Choose at least one duration.';
        if ($mode === 'range' && (!$min || ($max !== null && $max < $min))) return 'Check the duration range (from ≤ to).';

        $weekdays = array_values(array_unique(array_map('intval', $data['start_weekdays'] ?? [])));
        sort($weekdays);
        if (isset($data['start_weekdays']) && is_array($data['start_weekdays']) && !$weekdays) {
            return 'Choose at least one start day.';
        }

        DB::transaction(function () use ($tour, $all_year, $seasons, $mode, $days, $min, $max, $weekdays, $data) {
            $tour->all_year = $all_year;
            $tour->duration_mode = $mode;
            $tour->duration_days = $mode === 'fixed' ? $days : null;
            $tour->duration_min = $mode === 'range' ? $min : null;
            $tour->duration_max = $mode === 'range' ? ($max ?? $min) : null;
            $tour->start_weekdays = (!$weekdays || count($weekdays) >= 7) ? null : $weekdays;
            $tour->booking_lead_days = max(0, min(365, (int) ($data['lead_days'] ?? 0)));
            $tour->save();

            Tour_season::where('tour_id', $tour->id)->delete();
            $now = now();
            foreach ($seasons as $s) {
                Tour_season::insert($s + ['tour_id' => $tour->id, 'created_at' => $now, 'updated_at' => $now]);
            }
        });

        return null;
    }

    /** "1 Dec – 15 Mar, 1 Jul – 31 Aug" or "All year" — for mails / admin. */
    public static function season_text(Tour $tour): string
    {
        if ($tour->all_year) return 'All year';
        $seasons = self::seasons($tour->id);
        if ($seasons->isEmpty()) return 'All year';
        return $seasons->map(fn ($s) => $s->from_day . ' ' . date('M', mktime(0, 0, 0, $s->from_month, 1)) . ' – ' . $s->to_day . ' ' . date('M', mktime(0, 0, 0, $s->to_month, 1)))->implode(', ');
    }
}

<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Shop\Tour;
use App\Models\Shop\Tour_price;

/**
 * Tour price calculator — docs/FUNCTIONS_DOCS/shop/tour_price_calculator.md
 *
 * A tour has any number of rules (tour_prices). For a chosen duration + group size
 * the matching rule is: same `days`, persons_min ≤ persons ≤ persons_max (NULL = no
 * upper limit); when several match, the narrowest range wins. per_person → price ×
 * persons, per_group → price. No match → price "on request" (null).
 *
 * The shop form runs the same rule in JS for the live preview; the server always
 * recalculates when the reservation is saved (the client total is never trusted).
 */
class TourPriceService
{
    const TYPES = ['per_person', 'per_group'];
    // prices are calculated in euro (the column stays, so another currency can come back later)
    const CURRENCIES = ['EUR'];
    const DEFAULT_CURRENCY = 'EUR';
    const SYMBOLS = ['GEL' => '₾', 'USD' => '$', 'EUR' => '€'];

    public static function rules($tour_id)
    {
        return Tour_price::where('tour_id', $tour_id)
            ->orderBy('days')->orderBy('persons_min')
            ->get(['id', 'days', 'persons_min', 'persons_max', 'price', 'price_type']);
    }

    /** Public payload for the calculator. */
    public static function public_prices(Tour $tour): array
    {
        $rules = self::rules($tour->id);
        return [
            'currency' => self::currency($tour),
            'symbol' => self::symbol(self::currency($tour)),
            'durations' => $rules->pluck('days')->unique()->sort()->values(),
            'rules' => $rules->values(),
            // season, duration options, start days, lead time (TourScheduleService)
            'schedule' => TourScheduleService::public_schedule($tour),
        ];
    }

    /**
     * @return array|null  null = no rule matches (price on request)
     */
    public static function quote(Tour $tour, int $days, int $persons): ?array
    {
        $match = self::rules($tour->id)
            ->filter(fn ($r) => $r->days === $days
                && $persons >= $r->persons_min
                && ($r->persons_max === null || $persons <= $r->persons_max))
            ->sortBy(fn ($r) => $r->persons_max === null ? PHP_INT_MAX : $r->persons_max - $r->persons_min)
            ->first();

        if (!$match) return null;

        $total = $match->price_type === 'per_group' ? $match->price : $match->price * $persons;
        $currency = self::currency($tour);

        return [
            'rule_id' => $match->id,
            'days' => $days,
            'persons' => $persons,
            'persons_min' => $match->persons_min,
            'persons_max' => $match->persons_max,
            'price_type' => $match->price_type,
            'unit_price' => round($match->price, 2),
            'total' => round($total, 2),
            'per_person' => round($total / max($persons, 1), 2),
            'currency' => $currency,
            'symbol' => self::symbol($currency),
        ];
    }

    /**
     * Replace all rules of a tour. Rows are validated by the controller;
     * here only overlaps are checked (same days + overlapping persons range).
     *
     * @return string|null  error message, or null when saved
     */
    public static function save(Tour $tour, string $currency, array $rows): ?string
    {
        $clean = [];
        foreach ($rows as $row) {
            $clean[] = [
                'days' => (int) $row['days'],
                'persons_min' => (int) $row['persons_min'],
                'persons_max' => (isset($row['persons_max']) && $row['persons_max'] !== '' && $row['persons_max'] !== null) ? (int) $row['persons_max'] : null,
                'price' => round((float) $row['price'], 2),
                'price_type' => in_array($row['price_type'] ?? '', self::TYPES, true) ? $row['price_type'] : 'per_person',
            ];
        }

        foreach ($clean as $i => $a) {
            if ($a['persons_max'] !== null && $a['persons_max'] < $a['persons_min']) {
                return 'Row ' . ($i + 1) . ': "persons to" is smaller than "persons from".';
            }
            foreach ($clean as $j => $b) {
                if ($j <= $i || $a['days'] !== $b['days']) continue;
                $a_max = $a['persons_max'] ?? PHP_INT_MAX;
                $b_max = $b['persons_max'] ?? PHP_INT_MAX;
                if ($a['persons_min'] <= $b_max && $b['persons_min'] <= $a_max) {
                    return 'Rows ' . ($i + 1) . ' and ' . ($j + 1) . ' overlap (' . $a['days'] . ' day(s), same group size).';
                }
            }
        }

        DB::transaction(function () use ($tour, $currency, $clean) {
            $tour->price_currency = in_array($currency, self::CURRENCIES, true) ? $currency : self::DEFAULT_CURRENCY;
            $tour->save();
            Tour_price::where('tour_id', $tour->id)->delete();
            $now = now();
            foreach ($clean as $row) {
                Tour_price::insert($row + ['tour_id' => $tour->id, 'created_at' => $now, 'updated_at' => $now]);
            }
        });

        return null;
    }

    public static function currency(Tour $tour): string
    {
        return in_array($tour->price_currency, self::CURRENCIES, true) ? $tour->price_currency : self::DEFAULT_CURRENCY;
    }

    public static function symbol($currency): string
    {
        return self::SYMBOLS[$currency ?: self::DEFAULT_CURRENCY] ?? (string) $currency;
    }

    /**
     * "1 200 GEL" — for mails. The currency code, not the symbol: the Lari sign (₾)
     * is missing from many mail-client fonts and shows as an empty box.
     */
    public static function format($amount, $currency): string
    {
        if ($amount === null || $amount === '') return '';
        $n = (float) $amount;
        $text = fmod($n, 1.0) == 0.0 ? number_format($n, 0, '.', ' ') : number_format($n, 2, '.', ' ');
        return $text . ' ' . ($currency ?: self::DEFAULT_CURRENCY);
    }
}

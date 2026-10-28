<?php

namespace App\Services;

use App\Models\Summit\Summit;
use RuntimeException;
use XMLReader;

/**
 * GPS tracks for summit ascents (GPX upload or a Strava activity):
 * parse → stats → "does it reach the summit?" → simplified points to store.
 *
 * A point is [lat, lng, ele|null, unix time|null].
 */
class SummitTrackService
{
    // the track must pass this close to the summit's coordinates
    const VERIFY_RADIUS_M = 150;

    // GPS altitude noise: the altitude is averaged over ±GAIN_SMOOTH points,
    // then climbs smaller than GAIN_THRESHOLD_M aren't counted
    const GAIN_SMOOTH = 3;
    const GAIN_THRESHOLD_M = 4;

    const MAX_POINTS = 200000;      // refuse bigger files
    const STORE_MAX_POINTS = 1500;  // simplified track kept for the owner's map

    /** @return array<int, array{0: float, 1: float, 2: ?float, 3: ?int}> */
    public static function parse_gpx(string $path): array
    {
        $reader = new XMLReader();
        if (!$reader->open($path, null, LIBXML_NONET)) {
            throw new RuntimeException('not_gpx');
        }
        $points = [];
        $current = null;
        $field = null;
        $has_gpx_root = false;
        libxml_use_internal_errors(true);
        try {
            while (@$reader->read()) {
                if ($reader->nodeType === XMLReader::ELEMENT) {
                    $name = $reader->localName;
                    if ($name === 'gpx') $has_gpx_root = true;
                    if ($name === 'trkpt' || $name === 'rtept') {
                        $lat = $reader->getAttribute('lat');
                        $lon = $reader->getAttribute('lon');
                        if (is_numeric($lat) && is_numeric($lon)) {
                            $current = [(float) $lat, (float) $lon, null, null];
                        }
                        if ($reader->isEmptyElement && $current) { $points[] = $current; $current = null; }
                    } elseif ($current && ($name === 'ele' || $name === 'time')) {
                        $field = $name;
                    }
                } elseif ($reader->nodeType === XMLReader::TEXT && $current && $field) {
                    $value = trim($reader->value);
                    if ($field === 'ele' && is_numeric($value)) $current[2] = (float) $value;
                    if ($field === 'time' && ($ts = strtotime($value)) !== false) $current[3] = $ts;
                } elseif ($reader->nodeType === XMLReader::END_ELEMENT) {
                    $name = $reader->localName;
                    if ($name === 'ele' || $name === 'time') $field = null;
                    if (($name === 'trkpt' || $name === 'rtept') && $current) {
                        $points[] = $current;
                        $current = null;
                        if (count($points) > self::MAX_POINTS) throw new RuntimeException('too_many_points');
                    }
                }
            }
        } finally {
            $reader->close();
            libxml_clear_errors();
        }
        if (!$has_gpx_root) throw new RuntimeException('not_gpx');
        if (count($points) < 2) throw new RuntimeException('no_points');
        return $points;
    }

    /**
     * Strava streams (key_by_type=true): latlng [[lat, lng]…], altitude […],
     * time [seconds since start…] + the activity's start (unix).
     */
    public static function from_strava_streams(array $streams, ?int $start_ts): array
    {
        $latlng = $streams['latlng']['data'] ?? [];
        $alt = $streams['altitude']['data'] ?? [];
        $time = $streams['time']['data'] ?? [];
        $points = [];
        foreach ($latlng as $i => $ll) {
            if (!is_array($ll) || count($ll) < 2) continue;
            $points[] = [
                (float) $ll[0], (float) $ll[1],
                isset($alt[$i]) ? (float) $alt[$i] : null,
                ($start_ts !== null && isset($time[$i])) ? $start_ts + (int) $time[$i] : null,
            ];
        }
        if (count($points) < 2) throw new RuntimeException('no_points');
        return $points;
    }

    /** Stats + verification against a summit. */
    public static function analyse(array $points, Summit $summit): array
    {
        $distance = 0.0;
        $gain = 0.0;
        $max_ele = null;
        $anchor = null;
        $closest = null;
        $has_summit = is_numeric($summit->latitude) && is_numeric($summit->longitude);
        $prev = null;
        $smooth = self::smoothed_elevations($points);

        foreach ($points as $i => $p) {
            if ($prev) $distance += self::haversine($prev[0], $prev[1], $p[0], $p[1]);
            if ($p[2] !== null) $max_ele = $max_ele === null ? $p[2] : max($max_ele, $p[2]);
            if (($e = $smooth[$i]) !== null) {
                if ($anchor === null) $anchor = $e;
                elseif ($e - $anchor >= self::GAIN_THRESHOLD_M) { $gain += $e - $anchor; $anchor = $e; }
                elseif ($anchor - $e >= self::GAIN_THRESHOLD_M) { $anchor = $e; }
            }
            if ($has_summit) {
                $d = self::haversine($p[0], $p[1], (float) $summit->latitude, (float) $summit->longitude);
                if ($closest === null || $d < $closest) $closest = $d;
            }
            $prev = $p;
        }

        $first_ts = null;
        $last_ts = null;
        foreach ($points as $p) { if ($p[3] !== null) { $first_ts = $p[3]; break; } }
        for ($i = count($points) - 1; $i >= 0; $i--) { if ($points[$i][3] !== null) { $last_ts = $points[$i][3]; break; } }

        return [
            'verified'         => $closest !== null && $closest <= self::VERIFY_RADIUS_M,
            'closest_m'        => $closest !== null ? (int) round($closest) : null,
            'distance_m'       => (int) round($distance),
            'elevation_gain_m' => (int) round($gain),
            'max_elevation_m'  => $max_ele !== null ? (int) round($max_ele) : null,
            'duration_s'       => ($first_ts && $last_ts && $last_ts > $first_ts) ? $last_ts - $first_ts : null,
            'started_at'       => $first_ts ? date('Y-m-d H:i:s', $first_ts) : null,
        ];
    }

    // moving average of the altitude over ±GAIN_SMOOTH points (nulls skipped)
    private static function smoothed_elevations(array $points): array
    {
        $n = count($points);
        $out = array_fill(0, $n, null);
        $k = self::GAIN_SMOOTH;
        for ($i = 0; $i < $n; $i++) {
            if ($points[$i][2] === null) continue;
            $sum = 0.0;
            $count = 0;
            for ($j = max(0, $i - $k); $j <= min($n - 1, $i + $k); $j++) {
                if ($points[$j][2] !== null) { $sum += $points[$j][2]; $count++; }
            }
            $out[$i] = $sum / $count;
        }
        return $out;
    }

    /** Ramer–Douglas–Peucker in metres, loosened until ≤ STORE_MAX_POINTS. → [[lat, lng, ele]…] */
    public static function simplify(array $points): array
    {
        $tolerance = 5.0;
        do {
            $kept = self::rdp($points, $tolerance);
            $tolerance *= 1.8;
        } while (count($kept) > self::STORE_MAX_POINTS && $tolerance < 2000);

        return array_map(fn ($p) => [round($p[0], 6), round($p[1], 6), $p[2] !== null ? round($p[2], 1) : null], $kept);
    }

    private static function rdp(array $points, float $tolerance): array
    {
        $n = count($points);
        if ($n <= 2) return $points;
        // local equirectangular projection (metres) — fine at track scale
        $lat0 = deg2rad($points[0][0]);
        $xy = array_map(fn ($p) => [deg2rad($p[1]) * cos($lat0) * 6371000, deg2rad($p[0]) * 6371000], $points);
        $keep = array_fill(0, $n, false);
        $keep[0] = $keep[$n - 1] = true;
        $stack = [[0, $n - 1]];
        while ($stack) {
            [$a, $b] = array_pop($stack);
            $max = 0.0;
            $index = -1;
            for ($i = $a + 1; $i < $b; $i++) {
                $d = self::segment_distance($xy[$i], $xy[$a], $xy[$b]);
                if ($d > $max) { $max = $d; $index = $i; }
            }
            if ($index !== -1 && $max > $tolerance) {
                $keep[$index] = true;
                $stack[] = [$a, $index];
                $stack[] = [$index, $b];
            }
        }
        $out = [];
        foreach ($points as $i => $p) if ($keep[$i]) $out[] = $p;
        return $out;
    }

    private static function segment_distance(array $p, array $a, array $b): float
    {
        $dx = $b[0] - $a[0];
        $dy = $b[1] - $a[1];
        $len2 = $dx * $dx + $dy * $dy;
        if ($len2 == 0.0) return hypot($p[0] - $a[0], $p[1] - $a[1]);
        $t = max(0, min(1, (($p[0] - $a[0]) * $dx + ($p[1] - $a[1]) * $dy) / $len2));
        return hypot($p[0] - ($a[0] + $t * $dx), $p[1] - ($a[1] + $t * $dy));
    }

    public static function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $r = 6371000;
        $dlat = deg2rad($lat2 - $lat1);
        $dlon = deg2rad($lon2 - $lon1);
        $a = sin($dlat / 2) ** 2 + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dlon / 2) ** 2;
        return 2 * $r * asin(min(1, sqrt($a)));
    }
}

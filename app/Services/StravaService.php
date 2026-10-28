<?php

namespace App\Services;

use App\Models\StravaConnection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Strava API (OAuth 2) — used only to import an activity as the GPS track of
 * a summit ascent. Strava's API terms: a user's Strava data may be shown only
 * to that user, so tracks imported from Strava keep just the derived numbers
 * (verified, distance, gain…) — see SummitTrackController.
 *
 * .env: STRAVA_CLIENT_ID, STRAVA_CLIENT_SECRET (+ optional STRAVA_REDIRECT_URI;
 * default https://<USER_PAGE_URL>/api/strava/callback). In the Strava app
 * settings, "Authorization Callback Domain" must be the user-panel host.
 */
class StravaService
{
    const API = 'https://www.strava.com/api/v3';
    const OAUTH = 'https://www.strava.com/oauth';
    const SCOPE = 'read,activity:read_all';
    const STATE_TTL = 1800; // seconds the "connect" link stays valid

    public static function configured(): bool
    {
        return (bool) config('services.strava.client_id') && (bool) config('services.strava.client_secret');
    }

    public static function redirect_uri(): string
    {
        return config('services.strava.redirect')
            ?: rtrim(env('APP_SSH', 'https://') . env('USER_PAGE_URL', 'user.climbing.ge'), '/') . '/api/strava/callback';
    }

    // the "Connect with Strava" link; state carries who asked (encrypted, expires)
    public static function authorize_url(int $user_id): string
    {
        $state = Crypt::encryptString(json_encode(['u' => $user_id, 't' => time()]));
        return self::OAUTH . '/authorize?' . http_build_query([
            'client_id'       => config('services.strava.client_id'),
            'redirect_uri'    => self::redirect_uri(),
            'response_type'   => 'code',
            'approval_prompt' => 'auto',
            'scope'           => self::SCOPE,
            'state'           => $state,
        ]);
    }

    /** state → user id (or null when forged / expired) */
    public static function user_from_state(?string $state): ?int
    {
        if (!$state) return null;
        try {
            $data = json_decode(Crypt::decryptString($state), true);
        } catch (\Throwable $e) {
            return null;
        }
        if (!is_array($data) || empty($data['u']) || empty($data['t']) || time() - (int) $data['t'] > self::STATE_TTL) return null;
        return (int) $data['u'];
    }

    public static function connect(int $user_id, string $code, ?string $granted_scope): StravaConnection
    {
        $response = Http::asForm()->timeout(15)->post(self::OAUTH . '/token', [
            'client_id'     => config('services.strava.client_id'),
            'client_secret' => config('services.strava.client_secret'),
            'code'          => $code,
            'grant_type'    => 'authorization_code',
        ]);
        if (!$response->ok() || !$response->json('access_token')) throw new RuntimeException('strava_token');

        $athlete = $response->json('athlete') ?: [];
        return StravaConnection::updateOrCreate(['user_id' => $user_id], [
            'athlete_id'    => (int) ($athlete['id'] ?? 0),
            'athlete_name'  => trim(($athlete['firstname'] ?? '') . ' ' . ($athlete['lastname'] ?? '')) ?: null,
            'access_token'  => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
            'expires_at'    => date('Y-m-d H:i:s', (int) $response->json('expires_at')),
            'scope'         => $granted_scope,
        ]);
    }

    // a valid access token (refreshed when it expires within 2 minutes)
    public static function token(StravaConnection $connection): string
    {
        if ($connection->expires_at && $connection->expires_at->getTimestamp() > time() + 120) {
            return $connection->access_token;
        }
        $response = Http::asForm()->timeout(15)->post(self::OAUTH . '/token', [
            'client_id'     => config('services.strava.client_id'),
            'client_secret' => config('services.strava.client_secret'),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $connection->refresh_token,
        ]);
        if (!$response->ok() || !$response->json('access_token')) throw new RuntimeException('strava_refresh');
        $connection->update([
            'access_token'  => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
            'expires_at'    => date('Y-m-d H:i:s', (int) $response->json('expires_at')),
        ]);
        return $connection->access_token;
    }

    private static function get(StravaConnection $connection, string $path, array $query = [])
    {
        $response = Http::withToken(self::token($connection))->timeout(20)->get(self::API . $path, $query);
        if ($response->status() === 401) throw new RuntimeException('strava_unauthorized');
        if ($response->status() === 429) throw new RuntimeException('strava_rate_limit');
        if (!$response->ok()) throw new RuntimeException('strava_error');
        return $response->json();
    }

    /** Activities between two unix times (newest first), only the fields the picker needs. */
    public static function activities(StravaConnection $connection, int $after, int $before): array
    {
        $list = self::get($connection, '/athlete/activities', ['after' => $after, 'before' => $before, 'per_page' => 50]);
        return collect($list ?: [])
            ->map(fn ($a) => [
                'id'                   => $a['id'],
                'name'                 => $a['name'] ?? '',
                'type'                 => $a['sport_type'] ?? ($a['type'] ?? ''),
                'start_date'           => $a['start_date'] ?? null,
                'distance_m'           => (int) round($a['distance'] ?? 0),
                'elevation_gain_m'     => (int) round($a['total_elevation_gain'] ?? 0),
                'moving_time_s'        => (int) ($a['moving_time'] ?? 0),
                'has_gps'              => !empty($a['map']['summary_polyline']) || !empty($a['start_latlng']),
            ])
            ->sortByDesc('start_date')
            ->values()
            ->all();
    }

    /** One activity's start (unix) + its GPS / altitude / time streams. */
    public static function activity_streams(StravaConnection $connection, int $activity_id): array
    {
        $activity = self::get($connection, '/activities/' . $activity_id);
        $streams = self::get($connection, '/activities/' . $activity_id . '/streams', [
            'keys' => 'latlng,altitude,time', 'key_by_type' => 'true',
        ]);
        $start = !empty($activity['start_date']) ? strtotime($activity['start_date']) : null;
        return [$start ?: null, $streams ?: []];
    }

    public static function disconnect(StravaConnection $connection): void
    {
        try {
            Http::asForm()->timeout(10)->post(self::OAUTH . '/deauthorize', ['access_token' => self::token($connection)]);
        } catch (\Throwable $e) {
            // revoked on Strava's side already, or offline — the local row goes anyway
        }
        $connection->delete();
    }
}

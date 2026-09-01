<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Client for UBIA's cloud backend (the platform behind the "ucon" app that
 * A8-style 4G trail cameras use — see docs/LIVE_CAMERAS.md).
 *
 * Request/response shapes below are not guessed: they were confirmed from a
 * real HAR capture of https://web.ubianet.com/sign/sign-in on 2026-09-01.
 * Two things that look like typos are not:
 *  - the login "app" field must be the exact string "UBox" (capitalised).
 *  - the password hash is base64URL (- and _), not standard base64.
 * Getting either wrong silently returns "Invalid account or password".
 *
 * One shared account (UBIA_ACCOUNT_EMAIL / UBIA_ACCOUNT_PASSWORD in .env)
 * is used for every 'ubia'-sourced live camera — cameras are added to that
 * account's UBIA dashboard (Devices > Share with friends, or paired
 * directly), not per-camera credentials in this app.
 */
class UbiaCameraService
{
    private const BASE_URL  = 'https://web.ubianet.com';
    private const CACHE_KEY = 'ubia_auth_session';

    /**
     * Recent cloud-synced photos for one device, newest first.
     * Returns [] if not logged in, the device has no active cloud storage
     * plan, or the call otherwise fails — this is a display feature, not
     * something that should ever break the page it's shown on.
     */
    public static function getRecentPhotos(string $deviceUid, int $hours = 48): array
    {
        $session = self::getSession();
        if (!$session) {
            return [];
        }

        $response = self::authedPost('/api/user/cloud_list', [
            'summer_time'  => 0,
            'time_revised' => true,
            'device_uid'   => [$deviceUid],
            'time_diff'    => 0,
            'page'         => 1,
            'timestamp'    => [now()->subHours($hours)->timestamp, now()->timestamp],
        ], $session);

        // Token may have expired server-side even though our cached
        // token_valid_hours says it shouldn't have — one forced re-login retry.
        if (($response['code'] ?? null) !== 0) {
            $session = self::getSession(forceRelogin: true);
            if (!$session) {
                return [];
            }
            $response = self::authedPost('/api/user/cloud_list', [
                'summer_time'  => 0,
                'time_revised' => true,
                'device_uid'   => [$deviceUid],
                'time_diff'    => 0,
                'page'         => 1,
                'timestamp'    => [now()->subHours($hours)->timestamp, now()->timestamp],
            ], $session);
        }

        $items = $response['data']['list'] ?? [];

        return collect($items)
            ->sortByDesc('event_time')
            ->map(fn ($item) => [
                'captured_at' => isset($item['event_time']) ? date('c', $item['event_time']) : null,
                'image_url'   => $item['cloud_image_url'] ?? null,
                'battery'     => $item['battery'] ?? null,
            ])
            ->filter(fn ($item) => $item['image_url'])
            ->values()
            ->all();
    }

    /** Devices visible to the shared UBIA account, for the admin picker. */
    public static function getDevices(): array
    {
        $session = self::getSession();
        if (!$session) {
            return [];
        }

        $response = self::authedPost('/api/user/qry/device/list/v2', [], $session);

        return $response['data']['items'] ?? [];
    }

    private static function getSession(bool $forceRelogin = false): ?array
    {
        if (!$forceRelogin) {
            $cached = Cache::get(self::CACHE_KEY);
            if ($cached) {
                return $cached;
            }
        }

        $email    = trim((string) env('UBIA_ACCOUNT_EMAIL'));
        $password = trim((string) env('UBIA_ACCOUNT_PASSWORD'));

        if (!$email || !$password) {
            return null;
        }

        $response = Http::withHeaders([
            'accept'                => 'application/json, text/plain, */*',
            'content-type'          => 'application/json',
            'x-ubiaapi-callcontext' => 'source=web&app=ubox&ver=0.0.1&uuid=&os=Linux&osver=x86_64&lang=en-US',
        ])->post(self::BASE_URL . '/api/v3/login', [
            'app'      => 'UBox',
            'account'  => $email,
            'password' => self::hashPassword($password),
        ]);

        $data = $response->json();

        if (($data['code'] ?? null) !== 0 || empty($data['data']['Token'])) {
            report(new \RuntimeException('UBIA login failed: ' . ($data['msg'] ?? 'unknown error')));
            return null;
        }

        $session = [
            'token' => $data['data']['Token'],
            'uuid'  => $data['data']['uuid'] ?? '',
        ];

        // token_valid_hours is 696 (~29 days) in practice; cache well under
        // that so we always re-login before the real server-side expiry.
        $validHours = max(1, (int) ($data['data']['token_valid_hours'] ?? 24) - 24);
        Cache::put(self::CACHE_KEY, $session, now()->addHours($validHours));

        return $session;
    }

    private static function authedPost(string $path, array $body, array $session): array
    {
        $response = Http::withHeaders([
            'accept'                => 'application/json, text/plain, */*',
            'content-type'          => 'application/json',
            'x-ubia-auth-usertoken' => $session['token'],
            'x-ubiaapi-callcontext' => "source=web&app=ubox&ver=0.0.1&uuid={$session['uuid']}&os=Linux&osver=x86_64&lang=en-US",
        ])->post(self::BASE_URL . $path, $body);

        return $response->json() ?? [];
    }

    private static function hashPassword(string $password): string
    {
        $raw     = hash_hmac('sha1', $password, '', true);
        $urlSafe = strtr(base64_encode($raw), '+/', '-_');

        return substr($urlSafe, 0, -1) . ',';
    }
}

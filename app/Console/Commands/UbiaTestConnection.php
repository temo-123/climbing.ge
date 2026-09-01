<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

/**
 * One-off diagnostic for the A8/ucon trail camera.
 *
 * ucon is a white-labeled app on UBIA's cloud backend. This account's data
 * lives on the web.ubianet.com regional deployment (not portal.ubianet.com,
 * which the public ubox_camera_api reference project targets and which
 * rejects this account outright). Request/response shapes below were
 * confirmed against a real HAR capture of https://web.ubianet.com/sign/sign-in
 * on 2026-09-01 — not guessed.
 *
 * Not wired into the app anywhere else. Delete once validated.
 */
class UbiaTestConnection extends Command
{
    protected $signature   = 'app:ubia-test-connection';
    protected $description = 'Diagnostic: log in to the UBIA/ucon cloud backend and list recent camera photos';

    private const BASE_URL = 'https://web.ubianet.com';

    public function handle(): int
    {
        $email    = trim((string) env('UBIA_ACCOUNT_EMAIL'));
        $password = trim((string) env('UBIA_ACCOUNT_PASSWORD'));

        if (!$email || !$password) {
            $this->error('Set UBIA_ACCOUNT_EMAIL and UBIA_ACCOUNT_PASSWORD in .env first.');
            return self::FAILURE;
        }

        $this->line("Using email: [{$email}]  password length: " . strlen($password));
        $this->info('Logging in...');

        // Real captured body has exactly these 3 fields — no lang/device_token/device_type.
        $loginResponse = Http::withHeaders([
            'accept'                => 'application/json, text/plain, */*',
            'content-type'          => 'application/json',
            'x-ubiaapi-callcontext' => 'source=web&app=ubox&ver=0.0.1&uuid=&os=Linux&osver=x86_64&lang=en-US',
        ])->post(self::BASE_URL . '/api/v3/login', [
            'app'      => 'UBox',
            'account'  => $email,
            'password' => $this->hashPassword($password),
        ]);

        $loginData = $loginResponse->json();
        $this->line('login -> code=' . ($loginData['code'] ?? 'null') . ' msg=' . ($loginData['msg'] ?? '') . '  raw=' . json_encode($loginData));

        if (($loginData['code'] ?? null) !== 0) {
            $this->error('Login failed — see raw response above.');
            return self::FAILURE;
        }

        $token = $loginData['data']['Token'] ?? null;
        $uuid  = $loginData['data']['uuid'] ?? '';

        if (!$token) {
            $this->error('Login "succeeded" but no Token in response.');
            return self::FAILURE;
        }

        $this->info('Login OK. Fetching device list...');

        $devicesResponse = $this->ubiaPost('/api/user/qry/device/list/v2', [], $token, $uuid);
        $devices         = $devicesResponse['data']['items'] ?? [];
        $infos           = $devicesResponse['data']['infos'] ?? [];

        if (empty($devices)) {
            $this->warn('Logged in, but device_list returned no devices. Raw response:');
            $this->line(json_encode($devicesResponse));
            return self::FAILURE;
        }

        foreach ($devices as $device) {
            $uid  = $device['device_uid'] ?? null;
            $name = $device['device_name'] ?? '(unnamed)';
            $info = collect($infos)->firstWhere('device_uid', $uid);
            $this->info("Device: {$name}  [uid: {$uid}]  has_cloud_storage=" . json_encode($info['has_cloud_storage'] ?? null) . ' is_cloud_storage_opened=' . json_encode($info['is_cloud_storage_opened'] ?? null));

            if (!$uid) {
                continue;
            }

            // NOT confirmed against a real capture yet (Video Replay wasn't
            // clicked during the HAR capture) — best guess from the mobile
            // SDK's documented cloud_list shape. Expect this to need fixing.
            $cloudList = $this->ubiaPost('/api/user/cloud_list', [
                'summer_time'  => 0,
                'time_revised' => true,
                'device_uid'   => [$uid],
                'time_diff'    => 0,
                'page'         => 1,
                'timestamp'    => [now()->subDays(7)->timestamp, now()->timestamp],
            ], $token, $uuid);

            $this->line('  cloud_list raw: ' . json_encode($cloudList));
        }

        return self::SUCCESS;
    }

    private function ubiaPost(string $path, array $body, string $token, string $uuid): array
    {
        $response = Http::withHeaders([
            'accept'                => 'application/json, text/plain, */*',
            'content-type'          => 'application/json',
            'x-ubia-auth-usertoken' => $token,
            'x-ubiaapi-callcontext' => "source=web&app=ubox&ver=0.0.1&uuid={$uuid}&os=Linux&osver=x86_64&lang=en-US",
        ])->post(self::BASE_URL . $path, $body);

        return $response->json() ?? [];
    }

    /**
     * UBIA's login "hash": HMAC-SHA1 with an empty key over the plaintext
     * password, base64url-encoded (- and _ instead of + and /), with the
     * final character replaced by a comma. Not real security — just what
     * the client sends instead of the plaintext password. Confirmed against
     * a real captured request (the earlier standard-base64 version produced
     * a near-identical hash that differed only in +/- at one position, which
     * is what silently broke every login attempt before this).
     */
    private function hashPassword(string $password): string
    {
        $raw     = hash_hmac('sha1', $password, '', true);
        $base64  = base64_encode($raw);
        $urlSafe = strtr($base64, '+/', '-_');

        return substr($urlSafe, 0, -1) . ',';
    }
}

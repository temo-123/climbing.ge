<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

/**
 * One-off diagnostic for the A8/ucon trail camera.
 *
 * ucon is a white-labeled app on UBIA's shared cloud backend
 * (portal.ubianet.com) — the same backend also serves "ubox", "ybox", etc.
 * This command logs in with the account's own ucon credentials, lists the
 * paired devices, and fetches recent cloud-synced photos, purely to confirm
 * the protocol (reverse-engineered from https://github.com/JEMcats/ubox_camera_api)
 * actually works against this account before any pipeline is built on it.
 *
 * Not wired into the app anywhere else. Delete once validated.
 */
class UbiaTestConnection extends Command
{
    protected $signature   = 'app:ubia-test-connection';
    protected $description = 'Diagnostic: log in to the UBIA/ucon cloud backend and list recent camera photos';

    private const BASE_URL = 'https://portal.ubianet.com';

    public function handle(): int
    {
        $email    = env('UBIA_ACCOUNT_EMAIL');
        $password = env('UBIA_ACCOUNT_PASSWORD');

        if (!$email || !$password) {
            $this->error('Set UBIA_ACCOUNT_EMAIL and UBIA_ACCOUNT_PASSWORD in .env first.');
            return self::FAILURE;
        }

        $this->info('Logging in...');

        $loginResponse = Http::withHeaders([
            'accept'       => '*/*',
            'content-type' => 'application/json',
        ])->post(self::BASE_URL . '/api/v3/login', [
            'account'      => $email,
            'password'     => $this->hashPassword($password),
            'lang'         => 'en',
            'app'          => 'ucon',
            'device_token' => Str::random(30),
            'device_type'  => 2,
        ]);

        $loginData = $loginResponse->json();

        if (($loginData['code'] ?? null) !== 0) {
            $this->error('Login failed (app=ucon): ' . ($loginData['msg'] ?? $loginResponse->status()));
            $this->line('Retrying with app=ubox in case the "app" field is validated against a fixed enum...');

            $loginResponse = Http::withHeaders([
                'accept'       => '*/*',
                'content-type' => 'application/json',
            ])->post(self::BASE_URL . '/api/v3/login', [
                'account'      => $email,
                'password'     => $this->hashPassword($password),
                'lang'         => 'en',
                'app'          => 'ubox',
                'device_token' => Str::random(30),
                'device_type'  => 2,
            ]);

            $loginData = $loginResponse->json();

            if (($loginData['code'] ?? null) !== 0) {
                $this->error('Login failed (app=ubox too): ' . ($loginData['msg'] ?? $loginResponse->status()));
                $this->line('Raw response: ' . json_encode($loginData));
                return self::FAILURE;
            }
        }

        $token = $loginData['data']['Token'] ?? null;

        if (!$token) {
            $this->error('Login "succeeded" but no Token in response — dumping raw response:');
            $this->line(json_encode($loginData));
            return self::FAILURE;
        }

        $this->info('Login OK. Fetching device list...');

        $devicesResponse = $this->ubiaPost('/api/v2/user/device_list', [], $token);
        $devices         = $devicesResponse['data']['items'] ?? [];

        if (empty($devices)) {
            $this->warn('Logged in, but device_list returned no devices. Raw response:');
            $this->line(json_encode($devicesResponse));
            return self::FAILURE;
        }

        foreach ($devices as $device) {
            $uid  = $device['device_uid'] ?? null;
            $name = $device['device_name'] ?? '(unnamed)';
            $this->info("Device: {$name}  [uid: {$uid}]");

            if (!$uid) {
                continue;
            }

            $cloudList = $this->ubiaPost('/api/user/cloud_list', [
                'summer_time'  => 0,
                'time_revised' => true,
                'device_uid'   => [$uid],
                'time_diff'    => 0,
                'page'         => 1,
                'timestamp'    => [now()->subHours(48)->timestamp, now()->timestamp],
            ], $token);

            $items = $cloudList['data']['list'] ?? [];
            $this->line('  Cloud photos in last 48h: ' . count($items));

            foreach (array_slice($items, 0, 3) as $item) {
                $when = isset($item['event_time']) ? date('Y-m-d H:i:s', $item['event_time']) : '?';
                $this->line("   - {$when}  {$item['cloud_image_url']}");
            }
        }

        return self::SUCCESS;
    }

    private function ubiaPost(string $path, array $body, string $token): array
    {
        $response = Http::withHeaders([
            'accept'                  => '*/*',
            'content-type'            => 'application/json',
            'x-ubia-auth-usertoken'   => $token,
        ])->post(self::BASE_URL . $path, $body + ['token' => $token]);

        return $response->json() ?? [];
    }

    /**
     * UBIA's login "hash": HMAC-SHA1 with an empty key over the plaintext
     * password, base64-encoded, with the final character replaced by a comma.
     * Not real security — just what the client is expected to send instead
     * of the plaintext password. Reverse-engineered from ubox_camera_api's
     * hash_password.js.
     */
    private function hashPassword(string $password): string
    {
        $raw    = hash_hmac('sha1', $password, '', true);
        $base64 = base64_encode($raw);

        return substr($base64, 0, -1) . ',';
    }
}

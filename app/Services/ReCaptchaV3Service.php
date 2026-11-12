<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * reCAPTCHA v3 checks for every public form (login, register, comments, reviews, ascents,
 * reservations, contact…). Callers do `if ($captcha->isConfigured()) { …verify… }`.
 *
 * Broken key ≠ bot. If Google says the *secret itself* is unusable (project deleted,
 * invalid / missing secret — anything other than "this token is bad"), every form on the
 * site would refuse every visitor. Instead the service treats reCAPTCHA as not configured
 * (fail-open), writes a CRITICAL log line at most once an hour, and re-checks Google every
 * 10 minutes, so protection comes back by itself once the keys in .env are fixed.
 * RECAPTCHA_FAIL_OPEN=false keeps the old fail-closed behaviour.
 * Docs: docs/BACKEND/RECAPTCHA.md
 */
class ReCaptchaV3Service
{
    private $secretKey;
    private $mobileSecretKey;

    // Google error codes that only mean "this token is bad / missing" — the key itself works.
    private const TOKEN_ERRORS = ['invalid-input-response', 'missing-input-response', 'timeout-or-duplicate'];
    private const HEALTH_TTL = 600;   // seconds between key checks

    public function __construct()
    {
        $this->secretKey = env('GOOGLE_CAPTCHA_V3_SECRET_KEY');
        // Secret for a *separate* reCAPTCHA site key registered for the mobile
        // app's WebView. Left unset, the mobile fallback below is simply skipped
        // and behavior is unchanged. Once set, mobile tokens (minted with the
        // mobile site key) verify against this secret and get the lower
        // threshold below — a token can only validate against the secret whose
        // site key actually minted it, so this can't be spoofed by a client
        // just claiming to be mobile (see verifySmart()).
        $this->mobileSecretKey = env('GOOGLE_CAPTCHA_V3_MOBILE_SECRET_KEY');
    }

    /** Secret set AND Google accepts it. A broken key counts as "not configured" (fail-open). */
    public function isConfigured(): bool
    {
        if (empty($this->secretKey)) return false;
        return !$this->secretIsBroken();
    }

    public function verify($token, $ip = null, $minScore = 0.5)
    {
        if (!$this->isConfigured()) return empty($this->secretKey) ? false : true;
        return $this->verifyWithSecret($token, $this->secretKey, $minScore, $ip);
    }

    // Tries the web secret first (0.5 threshold); if that doesn't validate and a
    // mobile secret is configured, tries that instead (0.3 threshold — WebView
    // tokens run structurally lower with nothing on-page for v3 to analyze).
    // Which threshold applies is decided by which secret Google's siteverify
    // actually accepts the token against, not by anything the client asserts.
    public function verifySmart($token, $ip = null): bool
    {
        if (!empty($this->secretKey) && !$this->isConfigured()) return true;   // web key broken → fail-open

        if ($this->verifyWithSecret($token, $this->secretKey, 0.5, $ip)) {
            return true;
        }

        if (!empty($this->mobileSecretKey) && $this->verifyWithSecret($token, $this->mobileSecretKey, 0.3, $ip)) {
            return true;
        }

        return false;
    }

    /**
     * Is the web secret unusable? Asks Google with a dummy token (cached 10 min):
     * a working key answers "invalid-input-response"; a broken one answers
     * "invalid-input-secret", "Project #… has been deleted.", etc.
     * Network trouble is "unknown" → not broken (normal checks keep running).
     */
    public function secretIsBroken(): bool
    {
        if (empty($this->secretKey)) return false;
        if (!filter_var(env('RECAPTCHA_FAIL_OPEN', true), FILTER_VALIDATE_BOOLEAN)) return false;
        if (app()->runningUnitTests()) return false;

        $key = 'recaptcha_secret_health:' . substr(sha1($this->secretKey), 0, 16);
        $codes = Cache::remember($key, self::HEALTH_TTL, function () {
            try {
                $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret'   => $this->secretKey,
                    'response' => 'health-check',
                ]);
                if (!$response->successful()) return [];
                return (array) ($response->json('error-codes') ?? []);
            } catch (\Throwable $e) {
                return [];
            }
        });

        $broken = collect($codes)->contains(fn ($c) => !in_array($c, self::TOKEN_ERRORS, true));
        if ($broken) $this->reportBrokenKey($codes);
        return $broken;
    }

    private function reportBrokenKey(array $codes): void
    {
        if (!Cache::add('recaptcha_secret_broken_logged', 1, 3600)) return;
        try {
            Log::critical('reCAPTCHA web secret rejected by Google — form checks are skipped (fail-open) until GOOGLE_CAPTCHA_V3_SITE_KEY / GOOGLE_CAPTCHA_V3_SECRET_KEY are replaced.', [
                'error_codes' => $codes,
            ]);
        } catch (\Throwable $e) {
            // logging must never break a form
        }
    }

    private function verifyWithSecret($token, $secret, $minScore, $ip = null)
    {
        if (empty($secret) || empty($token)) {
            return false;
        }

        try {
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $ip,
            ]);

            if (!$response->successful()) {
                return false;
            }

            $result = $response->json();

            return isset($result['success'], $result['score'])
                && $result['success'] === true
                && $result['score'] >= $minScore;

        } catch (\Exception $e) {
            return false;
        }
    }
}

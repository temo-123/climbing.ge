<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReCaptchaV3Service
{
    private $secretKey;
    private $mobileSecretKey;

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

    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    public function verify($token, $ip = null, $minScore = 0.5)
    {
        return $this->verifyWithSecret($token, $this->secretKey, $minScore, $ip);
    }

    // Tries the web secret first (0.5 threshold); if that doesn't validate and a
    // mobile secret is configured, tries that instead (0.3 threshold — WebView
    // tokens run structurally lower with nothing on-page for v3 to analyze).
    // Which threshold applies is decided by which secret Google's siteverify
    // actually accepts the token against, not by anything the client asserts.
    public function verifySmart($token, $ip = null): bool
    {
        if ($this->verifyWithSecret($token, $this->secretKey, 0.5, $ip)) {
            return true;
        }

        if (!empty($this->mobileSecretKey) && $this->verifyWithSecret($token, $this->mobileSecretKey, 0.3, $ip)) {
            return true;
        }

        return false;
    }

    private function verifyWithSecret($token, $secret, $minScore, $ip = null)
    {
        if (empty($secret)) {
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


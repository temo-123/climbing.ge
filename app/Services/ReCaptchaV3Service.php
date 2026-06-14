<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ReCaptchaV3Service
{
    private $secretKey;

    public function __construct()
    {
        $this->secretKey = env('GOOGLE_CAPTCHA_V3_SECRET_KEY');
    }

    public function isConfigured(): bool
    {
        return !empty($this->secretKey);
    }

    public function verify($token, $ip = null, $minScore = 0.5)
    {
        try {
            $response = Http::timeout(5)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
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


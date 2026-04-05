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

    public function verify($token, $ip = null, $minScore = 0.5)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $this->secretKey,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        $result = $response->json();

        return $result['success'] && $result['score'] >= $minScore;
    }
}


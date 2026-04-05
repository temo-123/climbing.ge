<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReCaptchaV3Service;
use Illuminate\Http\Request;

class ReCaptchaController extends Controller
{
    protected $reCaptchaService;

    public function __construct(ReCaptchaV3Service $reCaptchaService)
    {
        $this->reCaptchaService = $reCaptchaService;
    }

    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $verified = $this->reCaptchaService->verify($request->token, $request->ip(), 0.5);

        return response()->json([
            'success' => $verified
        ]);
    }
}


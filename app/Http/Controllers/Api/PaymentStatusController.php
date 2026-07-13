<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TbcPaymentService;

class PaymentStatusController extends Controller
{
    // GET /api/payment/status — public, tells the frontend whether the TBC
    // online payment gateway is actually configured (TBC_PAY_*_CLIENT_ID/SECRET
    // present in .env) so it can hide the donation form / online checkout
    // option instead of offering a payment path that will fail.
    public function status()
    {
        return response()->json([
            'donation_enabled' => (new TbcPaymentService('donation'))->isConfigured(),
            'shop_enabled'      => (new TbcPaymentService('shop'))->isConfigured(),
        ]);
    }
}

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
    // donation_enabled is true when either card payment or bank transfer
    // (DONATION_TBC_IBAN) is available; the modal uses the two split flags
    // to decide which tabs to show.
    public function status()
    {
        $donationCard = (new TbcPaymentService('donation'))->isConfigured();
        $donationBank = (config('services.donation_bank.iban') ?? '') !== '';

        return response()->json([
            'donation_enabled'      => $donationCard || $donationBank,
            'donation_card_enabled' => $donationCard,
            'donation_bank_enabled' => $donationBank,
            'shop_enabled'          => (new TbcPaymentService('shop'))->isConfigured(),
        ]);
    }
}

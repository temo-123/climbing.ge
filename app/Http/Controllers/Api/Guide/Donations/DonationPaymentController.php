<?php

namespace App\Http\Controllers\Api\Guide\Donations;

use App\Http\Controllers\Controller;
use App\Models\Guide\Donation;
use App\Services\TbcPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DonationPaymentController extends Controller
{
    private TbcPaymentService $tbc;

    public function __construct()
    {
        $this->tbc = new TbcPaymentService('donation');
    }

    // POST /api/set_donation/process
    public function processDonation(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $authUser = \Auth::user();

        if ($authUser) {
            $name        = $authUser->name;
            $surname     = $authUser->surname;
            $email       = $authUser->email;
            $phoneNumber = $authUser->phone_number ?? null;
            $country     = $authUser->country ?? null;
            $age         = null;
            $userId      = $authUser->id;
        } else {
            $request->validate([
                'name'         => 'nullable|string|max:255',
                'surname'      => 'nullable|string|max:255',
                'country'      => 'nullable|string|max:255',
                'age'          => 'nullable|integer|min:1|max:150',
                'email'        => 'nullable|email|max:255',
                'phone_number' => 'nullable|string|max:20',
                'comment'      => 'nullable|string|max:1000',
            ]);
            $name        = $request->name;
            $surname     = $request->surname;
            $email       = $request->email;
            $phoneNumber = $request->phone_number;
            $country     = $request->country;
            $age         = $request->age;

            $userId = null;
            if ($email) {
                $matched = \App\Models\User::where('email', $email)->first();
                if ($matched) $userId = $matched->id;
            }
        }

        $donation = Donation::create([
            'name'         => $name,
            'surname'      => $surname,
            'country'      => $country,
            'age'          => $age,
            'email'        => $email,
            'phone_number' => $phoneNumber,
            'comment'      => $request->comment,
            'amount'       => $request->amount,
            'currency'     => 'GEL',
            'status'       => Donation::STATUS_PENDING,
            'user_id'      => $userId,
        ]);

        $checkoutUrl = $this->initiateTbcPayment($donation);

        if ($checkoutUrl) {
            return response()->json([
                'success'      => true,
                'message'      => 'Redirect to payment',
                'checkout_url' => $checkoutUrl,
                'donation_id'  => $donation->id,
                'donator'      => $this->donatorData($donation),
                'donation'     => $this->donationData($donation),
            ]);
        }

        return response()->json([
            'success'     => true,
            'message'     => 'Donation created',
            'donation_id' => $donation->id,
            'donator'     => $this->donatorData($donation),
            'donation'    => $this->donationData($donation),
        ]);
    }

    private function initiateTbcPayment(Donation $donation): ?string
    {
        $siteBase = rtrim(env('APP_SSH', 'http://') . env('SITE_URL', 'climbing.ge'), '/');

        $desc = 'Donation';
        if ($donation->name) {
            $desc = 'Donation from ' . $donation->name;
            if ($donation->surname) $desc .= ' ' . $donation->surname;
        }

        try {
            $result = $this->tbc->createOrder([
                'amount'            => ['currency' => 'GEL', 'total' => (float) $donation->amount],
                'intent'            => 'CAPTURE',
                'returnUrl'         => $siteBase . '/donation-success?donation_id=' . $donation->id,
                'cancelUrl'         => $siteBase . '/donation-cancel?donation_id=' . $donation->id,
                'callbackUrl'       => url('/api/set_donation/callback'),
                'merchantPaymentId' => 'donation_' . $donation->id,
                'lang'              => 'KA',
            ]);

            $paymentUrl = TbcPaymentService::extractPaymentUrl($result);

            if ($paymentUrl) {
                $donation->update([
                    'tbc_pay_id'     => $result['payId'],
                    'tbc_pay_status' => $result['status'] ?? 'CREATED',
                ]);
                return $paymentUrl;
            }
        } catch (\Exception $e) {
            Log::error('TBC Pay donation error: ' . $e->getMessage());
        }

        return null;
    }

    // POST /api/set_donation/callback  (no auth — called by TBC server)
    public function callback(Request $request)
    {
        $payId             = $request->input('PaymentId') ?? $request->input('payId');
        $merchantPaymentId = $request->input('MerchantPaymentId') ?? $request->input('merchantPaymentId');

        $donation = null;

        if ($merchantPaymentId && str_starts_with((string) $merchantPaymentId, 'donation_')) {
            $donationId = (int) substr($merchantPaymentId, 9);
            $donation   = Donation::find($donationId);
        }

        if (!$donation && $payId) {
            $donation = Donation::where('tbc_pay_id', $payId)->first();
        }

        if (!$donation) {
            Log::warning('TBC donation callback: donation not found', $request->all());
            return response()->json(['error' => 'Donation not found'], 404);
        }

        try {
            $tbcOrder  = $this->tbc->getOrder($donation->tbc_pay_id);
            $tbcStatus = $tbcOrder['status'] ?? null;
        } catch (\Exception $e) {
            Log::error('TBC donation callback: getOrder failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Could not verify payment'], 500);
        }

        $update = ['tbc_pay_status' => $tbcStatus];

        if (TbcPaymentService::isSucceeded($tbcStatus)) {
            $update['status'] = Donation::STATUS_PAID;
        } elseif (TbcPaymentService::isFailed($tbcStatus)) {
            $update['status'] = $tbcStatus === 'EXPIRED'
                ? Donation::STATUS_EXPIRED
                : Donation::STATUS_DECLINED;
        }

        $donation->update($update);

        return response()->json(['ok' => true]);
    }

    // GET /api/set_donation/status/{id}
    public function status(int $id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json(['success' => false, 'message' => 'Donation not found'], 404);
        }

        if ($donation->tbc_pay_id && !$donation->isPaid() && !$donation->isFailed()) {
            try {
                $tbcOrder  = $this->tbc->getOrder($donation->tbc_pay_id);
                $tbcStatus = $tbcOrder['status'] ?? null;

                if ($tbcStatus && $tbcStatus !== $donation->tbc_pay_status) {
                    $update = ['tbc_pay_status' => $tbcStatus];
                    if (TbcPaymentService::isSucceeded($tbcStatus)) {
                        $update['status'] = Donation::STATUS_PAID;
                    } elseif (TbcPaymentService::isFailed($tbcStatus)) {
                        $update['status'] = $tbcStatus === 'EXPIRED'
                            ? Donation::STATUS_EXPIRED
                            : Donation::STATUS_DECLINED;
                    }
                    $donation->update($update);
                    $donation->refresh();
                }
            } catch (\Exception $e) {
                Log::warning('TBC donation status check failed: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success'     => true,
            'donation_id' => $donation->id,
            'donator'     => $this->donatorData($donation),
            'donation'    => array_merge($this->donationData($donation), ['is_paid' => $donation->isPaid()]),
        ]);
    }

    // GET /api/get_donation/tbc_info  (bank transfer details for Georgian users)
    public function get_tbc_info(Request $request)
    {
        $ip = trim(explode(',', (string) (
            $request->header('CF-Connecting-IP')
            ?? $request->header('X-Forwarded-For')
            ?? $request->ip()
        ))[0]);

        if (!$this->isGeorgianIp($ip)) {
            return response()->json(['allowed' => false]);
        }

        return response()->json([
            'allowed'      => true,
            'iban'         => env('DONATION_TBC_IBAN', ''),
            'account_name' => env('DONATION_TBC_ACCOUNT_NAME', ''),
            'bank_code'    => env('DONATION_TBC_BANK_CODE', 'TBCBGE22'),
            'bank_name'    => env('DONATION_TBC_BANK_NAME', 'TBC Bank'),
        ]);
    }

    private function isGeorgianIp(string $ip): bool
    {
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return app()->environment('local', 'development');
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=countryCode");
            if ($response->successful()) {
                return $response->json('countryCode') === 'GE';
            }
        } catch (\Exception $e) {
            Log::warning('Geo IP check failed: ' . $e->getMessage());
        }

        return false;
    }

    private function donatorData(Donation $d): array
    {
        return [
            'name'         => $d->name,
            'surname'      => $d->surname,
            'country'      => $d->country,
            'age'          => $d->age,
            'email'        => $d->email,
            'phone_number' => $d->phone_number,
        ];
    }

    private function donationData(Donation $d): array
    {
        return [
            'amount'   => $d->amount,
            'currency' => $d->currency,
            'status'   => $d->status,
        ];
    }
}

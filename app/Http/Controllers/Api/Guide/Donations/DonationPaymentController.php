<?php

namespace App\Http\Controllers\Api\Guide\Donations;

use App\Http\Controllers\Controller;
use App\Models\Guide\Donation;
use App\Services\FlittPaymentService;
use Flitt\Result\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DonationPaymentController extends Controller
{
    /**
     * Process a donation payment
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processDonation(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);

        $authUser = \Auth::user();
        $userId = null;

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
            ]);
            $name        = $request->name;
            $surname     = $request->surname;
            $email       = $request->email;
            $phoneNumber = $request->phone_number;
            $country     = $request->country;
            $age         = $request->age;

            if ($email) {
                $matched = \App\Models\User::where('email', $email)->first();
                if ($matched) $userId = $matched->id;
            }
        }

        // Create donation record
        $donation = Donation::create([
            'name'         => $name,
            'surname'      => $surname,
            'country'      => $country,
            'age'          => $age,
            'email'        => $email,
            'phone_number' => $phoneNumber,
            'amount'       => $request->amount,
            'currency'     => 'GEL',
            'status'       => Donation::STATUS_PENDING,
            'user_id'      => $userId,
        ]);

        // Generate Flitt checkout URL
        $checkoutUrl = $this->generateFlittCheckoutUrl($donation);

        if ($checkoutUrl) {
            return response()->json([
                'success' => true,
                'message' => 'Redirect to payment',
                'checkout_url' => $checkoutUrl,
                'donation_id' => $donation->id,
                'donator' => [
                    'name' => $donation->name,
                    'surname' => $donation->surname,
                    'country' => $donation->country,
                    'age' => $donation->age,
                    'email' => $donation->email,
                    'phone_number' => $donation->phone_number,
                ],
                'donation' => [
                    'amount' => $donation->amount,
                    'currency' => $donation->currency,
                    'status' => $donation->status,
                ],
            ]);
        }

        // If no checkout URL generated, return donation details
        return response()->json([
            'success' => true,
            'message' => 'Donation created',
            'donation_id' => $donation->id,
            'donator' => [
                'name' => $donation->name,
                'surname' => $donation->surname,
                'country' => $donation->country,
                'age' => $donation->age,
                'email' => $donation->email,
                'phone_number' => $donation->phone_number,
            ],
            'donation' => [
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => $donation->status,
            ],
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Generate Flitt checkout URL for donation
     *
     * @param Donation $donation
     * @return string|null
     */
    private function generateFlittCheckoutUrl(Donation $donation): ?string
    {
        // Initialize Flitt configuration
        $this->initFlittConfig();

        $orderDescription = 'Donation';
        if ($donation->name) {
            $orderDescription = 'Donation from ' . $donation->name;
            if ($donation->surname) {
                $orderDescription .= ' ' . $donation->surname;
            }
        }

        $checkoutData = [
            'order_id' => $donation->id,
            'order_desc' => $orderDescription,
            'currency' => $donation->currency,
            'amount' => $donation->getAmountInCents(),
            'response_url' => config('app.frontend_url') . '/donation-success',
            'server_callback_url' => route('donations.callback'),
        ];

        try {
            $response = \Flitt\Checkout::url($checkoutData);
            $url = $response->getUrl();

            if ($url) {
                // Update donation with Flitt order ID and URLs
                $donation->update([
                    'flitt_order_id' => $donation->id,
                    'response_url' => $checkoutData['response_url'],
                    'server_callback_url' => $checkoutData['server_callback_url'],
                ]);

                return $url;
            }
        } catch (\Exception $e) {
            \Log::error('Flitt Checkout Error: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Initialize Flitt configuration
     */
    private function initFlittConfig(): void
    {
        \Flitt\Configuration::setMerchantId(config('flitt.merchant_id'));
        \Flitt\Configuration::setSecretKey(config('flitt.secret_key'));
    }

    /**
     * Handle Flitt payment callback
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        $this->initFlittConfig();

        try {
            // Get callback data
            $callbackData = $request->all();
            
            if (empty($callbackData)) {
                $callbackData = json_decode(file_get_contents('php://input'), true) ?? [];
            }

            if (empty($callbackData)) {
                return response()->json(['message' => 'No callback data received'], 400);
            }

            // Create Result object and validate signature
            $result = new Result($callbackData, config('flitt.secret_key'));

            if (!$result->isValid()) {
                \Log::warning('Invalid Flitt callback signature', ['data' => $callbackData]);
                return response()->json(['message' => 'Invalid signature'], 400);
            }

            $data = $result->getData();

            // Find donation by order_id
            $donation = Donation::find($data['order_id'] ?? null);

            if (!$donation) {
                \Log::error('Donation not found for callback', ['order_id' => $data['order_id'] ?? null]);
                return response()->json(['message' => 'Donation not found'], 404);
            }

            // Update donation status based on payment result
            if ($result->isApproved()) {
                $donation->update([
                    'status' => Donation::STATUS_PAID,
                    'flitt_order_id' => $data['order_id'] ?? $donation->id,
                ]);
                \Log::info('Donation payment approved', ['donation_id' => $donation->id]);
            } elseif ($result->isDeclined()) {
                $donation->update([
                    'status' => Donation::STATUS_DECLINED,
                ]);
                \Log::info('Donation payment declined', ['donation_id' => $donation->id]);
            } elseif ($result->isExpired()) {
                $donation->update([
                    'status' => Donation::STATUS_EXPIRED,
                ]);
                \Log::info('Donation payment expired', ['donation_id' => $donation->id]);
            } elseif ($result->isProcessing()) {
                $donation->update([
                    'status' => Donation::STATUS_PROCESSING,
                ]);
                \Log::info('Donation payment processing', ['donation_id' => $donation->id]);
            }

            return response()->json(['status' => 'ok']);
        } catch (\Exception $e) {
            \Log::error('Flitt callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Callback processing error'], 500);
        }
    }

    /**
     * Check donation payment status
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function status($id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'success' => false,
                'message' => 'Donation not found',
            ], 404);
        }

        // Optionally check status with Flitt API
        // $this->checkFlittStatus($donation);

        return response()->json([
            'success' => true,
            'donation_id' => $donation->id,
            'donator' => [
                'name' => $donation->name,
                'surname' => $donation->surname,
                'country' => $donation->country,
                'age' => $donation->age,
                'email' => $donation->email,
                'phone_number' => $donation->phone_number,
            ],
            'donation' => [
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => $donation->status,
                'is_paid' => $donation->isPaid(),
            ],
        ]);
    }

    /**
     * Optional: Check payment status with Flitt API
     *
     * @param Donation $donation
     * @return void
     */
    public function get_tbc_info(Request $request)
    {
        $ip = $request->header('CF-Connecting-IP')
            ?? $request->header('X-Forwarded-For')
            ?? $request->ip();

        // Take the first IP if comma-separated list
        $ip = trim(explode(',', $ip)[0]);

        $isGeorgian = $this->isGeorgianIp($ip);

        if (!$isGeorgian) {
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
        // Allow localhost / private ranges in development
        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.')) {
            return app()->environment('local', 'development');
        }

        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=countryCode");
            if ($response->successful()) {
                return $response->json('countryCode') === 'GE';
            }
        } catch (\Exception $e) {
            \Log::warning('Geo IP check failed: ' . $e->getMessage());
        }

        return false;
    }

    private function checkFlittStatus(Donation $donation): void
    {
        if (!$donation->flitt_order_id || $donation->isPaid() || $donation->isFailed()) {
            return;
        }

        $this->initFlittConfig();

        try {
            $statusResponse = \Flitt\Order::status(['order_id' => $donation->flitt_order_id]);
            
            if ($statusResponse->isApproved()) {
                $donation->update(['status' => Donation::STATUS_PAID]);
            }
        } catch (\Exception $e) {
            \Log::error('Flitt status check error: ' . $e->getMessage());
        }
    }

    /**
     * Create donation order (alternative method)
     *
     * @param Request $request
     * @param FlittPaymentService $flitt
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request, FlittPaymentService $flitt)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'age' => 'nullable|integer|min:1|max:150',
            'email' => 'nullable|email|max:255',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $donation = Donation::create([
            'name' => $validated['name'] ?? null,
            'surname' => $validated['surname'] ?? null,
            'country' => $validated['country'] ?? null,
            'age' => $validated['age'] ?? null,
            'email' => $validated['email'] ?? null,
            'phone_number' => $validated['phone_number'] ?? null,
            'amount' => $validated['amount'],
            'currency' => 'GEL',
            'status' => Donation::STATUS_PENDING,
        ]);

        $checkoutUrl = $flitt->createDonationCheckout($donation);

        return response()->json([
            'success' => true,
            'checkout_url' => $checkoutUrl,
            'donation_id' => $donation->id,
            'donator' => [
                'name' => $donation->name,
                'surname' => $donation->surname,
                'country' => $donation->country,
                'age' => $donation->age,
                'email' => $donation->email,
                'phone_number' => $donation->phone_number,
            ],
            'donation' => [
                'amount' => $donation->amount,
                'currency' => $donation->currency,
                'status' => $donation->status,
            ],
        ]);
    }
}


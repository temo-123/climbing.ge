<?php

namespace App\Http\Controllers\Api\Guide\Donations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'article_id' => 'nullable',
            'article_title' => 'nullable|string',
        ]);

        $amount = $validated['amount'];
        $articleId = $validated['article_id'] ?? null;
        $articleTitle = $validated['article_title'] ?? null;

        // Demo donation - in production, integrate payment gateway here
        // (e.g., Stripe, PayPal, etc.)
        
        return response()->json([
            'success' => true,
            'message' => 'Thank you for your donation!',
            'donation_amount' => $amount,
            'article_id' => $articleId,
            'article_title' => $articleTitle,
            'currency' => 'GEL',
            'status' => 'demo_mode',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}

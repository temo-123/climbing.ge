<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TourReservationRequest;

use App\Services\ReCaptchaV3Service;
use App\Services\TourReservationService;
use App\Services\TourScheduleService;

use App\Models\Shop\Tour;
use App\Models\Shop\Tour_reservation;

/**
 * Public tour reservation (shop tour page form) + email confirmation page.
 * Every reservation must be confirmed from the link mailed to its email —
 * docs/FUNCTIONS_DOCS/shop/tour_reservation_verification.md
 */
class UserTourReservationController extends Controller
{
    function create_reservation(TourReservationRequest $request){
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $tour = Tour::where('id', $request->tour_id)->first();
        if (!$tour) {
            return response()->json(['message' => 'Tour not found.'], 404);
        }

        $validated = $request->validated();

        // season, duration, start weekday, lead time (TourScheduleService)
        $problem = TourScheduleService::check_trip($tour, $validated['form_data']['check_in'], (int) $validated['form_data']['days']);
        if ($problem) {
            return response()->json([
                'message' => $problem['message'],
                'errors' => ['form_data.' . $problem['field'] => [$problem['message']]],
            ], 422);
        }

        // logged in → linked to the account at once; the email still has to be confirmed
        $user = auth('sanctum')->user();

        $reservation = TourReservationService::create($validated['form_data'], $tour->id, $user ?: null);

        return response()->json([
            'status' => 'verify_email',
            'email' => $reservation->email,
            // the price saved with the reservation (server calculation, null total = on request)
            'price' => [
                'days' => $reservation->days,
                'persons' => $reservation->persons,
                'total' => $reservation->price_total,
                'currency' => $reservation->price_currency,
            ],
            'message' => 'The reservation has been received! Please open the email we sent to ' . $reservation->email . ' and confirm the reservation.',
        ]);
    }

    /** Confirmation page: what is being confirmed (and is it already done). */
    function reservation_status(Request $request){
        $reservation = $this->find_with_token($request);
        if (!$reservation) {
            return response()->json(['error' => 'invalid_link'], 404);
        }

        return response()->json(TourReservationService::public_summary($reservation));
    }

    /** Confirmation page button. */
    function verify_reservation(Request $request){
        $reservation = $this->find_with_token($request);
        if (!$reservation) {
            return response()->json(['error' => 'invalid_link'], 404);
        }

        $changed = TourReservationService::verify($reservation, 'email');

        return response()->json([
            'status' => $changed ? 'verified' : 'already_verified',
            'reservation' => TourReservationService::public_summary($reservation->fresh()),
        ]);
    }

    private function find_with_token(Request $request){
        $reservation = Tour_reservation::where('id', (int) $request->reservation_id)->first();
        if (!$reservation || !TourReservationService::token_valid($reservation, $request->input('token'))) {
            return null;
        }
        return $reservation;
    }
}

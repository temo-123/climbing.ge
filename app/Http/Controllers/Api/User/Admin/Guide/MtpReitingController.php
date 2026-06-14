<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Mtp_review;
use App\Services\ReCaptchaV3Service;
use Auth;

class MtpReitingController extends Controller
{
    public function create_mtp_review(Request $request, $mtp_id)
    {
        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $user = $request->user();

        $already = Mtp_review::where('mtp_id', $mtp_id)
            ->where('user_id', $user->id)
            ->count();

        if ($already > 0) {
            return response()->json([
                'already_reviewed' => true,
                'message' => 'You already have a review for this route.',
            ], 422);
        }

        $review = new Mtp_review();
        $review->mtp_id  = $mtp_id;
        $review->user_id = $user->id;
        $review->stars   = $request->input('stars');
        $review->text    = $request->input('text');
        $review->save();

        return response()->json(['success' => true, 'message' => 'Thank you for your feedback!']);
    }

    public function del_mtp_review($review_id)
    {
        $review = Mtp_review::where('id', $review_id)
            ->where('user_id', Auth::id())
            ->first();
        if ($review) {
            $review->delete();
        }
        return response()->json(['success' => true]);
    }
}

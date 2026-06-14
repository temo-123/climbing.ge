<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\Mtp_review;
use Auth;

class MtpReitingController extends Controller
{
    public function get_all_mtp_reviews($mtp_id)
    {
        $reviews = Mtp_review::where('mtp_id', $mtp_id)->get();
        foreach ($reviews as $review) {
            $review->user;
        }
        return $reviews;
    }

    public function create_mtp_review(Request $request, $mtp_id)
    {
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

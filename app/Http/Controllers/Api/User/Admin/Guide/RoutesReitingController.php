<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Guide\Sport_route_review;
use App\Models\Guide\RouteAscent;
// use App\Models\Guide\Sport_route_review_user;

use App\Services\PermissionService;
use App\Services\ReCaptchaV3Service;

use Auth;

class RoutesReitingController extends Controller
{
    public function get_all_review() {
        if ($auth = PermissionService::authorize('routes_rewiew', 'show')) return $auth;
        // return Sport_route_review::get();

        if(Sport_route_review::count() > 0){
            $all_reviews = Sport_route_review::get();

            $reviews = [];
            foreach ($all_reviews as $review) {
                
                array_push($reviews, [
                    'review' => $review, 
                    'route' => $review->route,
                ]);
            }
            
            return $reviews;
        }
    }

    public function get_all_route_reviews(Request $request) {
        if ($auth = PermissionService::authorize('routes_rewiew', 'show')) return $auth;
        $rews = Sport_route_review::where('route_id', '=', $request->route_id)->get();
        foreach ($rews as $rew) {
            $rew -> user;
        }
        return $rews;
    }

    public function get_user_review() {
        if ($auth = PermissionService::authorize('routes_rewiew', 'show')) return $auth;
        $user = auth()->user();
        // return $user->sport_route_reviews;

        if($user->sport_route_reviews->count() > 0){
            $all_user_reviews = $user->sport_route_reviews;

            $reviews = [];
            foreach ($all_user_reviews as $review) {
                
                array_push($reviews, [
                    'review' => $review, 
                    'route' => $review->route,
                ]);
            }
            
            return $reviews;
        }
    }

    public function get_user_sport_routes_review() {
        if ($auth = PermissionService::authorize('routes_rewiew', 'show')) return $auth;
        // if (Auth::user()) {
        //     $user_review = Sport_route_review_user::where('user_id', '=', Auth::user()->id)->get();

        //     $routes_reiting = [];

        //     foreach ($user_review as $rev) {
        //         array_push($routes_reiting, [
        //             "review" => $rev->review,
        //             "route" => $rev->review->route,
        //         ]);
        //     }
        //     // dd($routes_reiting);
        //     return $routes_reiting;
        // }
    }

    public function create_route_review(Request $request) {
        if ($auth = PermissionService::authorize('routes_rewiew', 'add')) return $auth;

        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        $user = $request->user();

        $user_review_count = $user->sport_route_reviews
            ->where('route_id', '=', $request["route_id"])
            ->count();

        if ($user_review_count > 0) {
            return response()->json([
                'already_reviewed' => true,
                'message' => 'You already have a review for this route.',
            ], 422);
        }

        $review = new Sport_route_review;
        $review['route_id']    = $request["route_id"];
        $review['text']        = $request["text"];
        $review['stars']       = $request["stars"];
        $review['user_id']     = $user->id;
        $review->save();

        // "I climbed it" on the review form is an ascent: it goes to the
        // logbook (route_ascents), not onto the review row (October 2026)
        $ascent_logged = false;
        if (!empty($request['climbed'])) {
            $ascent_logged = (bool) self::log_ascent_from_review($user->id, $request["route_id"], $request["ascent_style"], $request["climbed_data"]);
        }

        return response()->json([
            'success' => true,
            'ascent_logged' => $ascent_logged,
            'message' => 'Thank you for your feedback!',
        ]);
    }

    // Review form styles → RouteAscent::STYLES ("second_go" is a redpoint on the 2nd try)
    const REVIEW_STYLE_MAP = [
        'onsite'    => ['onsight', 1],
        'onsight'   => ['onsight', 1],
        'flash'     => ['flash', 1],
        'second_go' => ['redpoint', 2],
        'redpoint'  => ['redpoint', null],
        'top_rope'  => ['toprope', null],
        'toprope'   => ['toprope', null],
    ];

    public static function log_ascent_from_review($user_id, $route_id, $style, $date)
    {
        [$ascent_style, $attempts] = self::REVIEW_STYLE_MAP[$style] ?? ['redpoint', null];
        $ascent_date = $date && strtotime($date) && strtotime($date) <= time() ? date('Y-m-d', strtotime($date)) : now()->toDateString();

        // same climber, route and day already in the logbook → don't double it
        $exists = RouteAscent::where('user_id', $user_id)->where('route_id', $route_id)->whereDate('ascent_date', $ascent_date)->exists();
        if ($exists) return null;

        return RouteAscent::create([
            'route_id'     => $route_id,
            'user_id'      => $user_id,
            'ascent_date'  => $ascent_date,
            'ascent_style' => $ascent_style,
            'attempts'     => $attempts,
        ]);
    }

    // private function create_user_review_relatione($review_id){
    //     if (Auth::user()) {
    //         $review_user_rel = new Sport_route_review_user;
    //         $review_user_rel['review_id'] = $review_id;
    //         $review_user_rel['user_id'] = Auth::user()->id;
    //         $review_user_rel->save();
    //     }
    // }

    public function get_actyve_review($review_id){
        if ($auth = PermissionService::authorize('routes_rewiew', 'show')) return $auth;
        return Sport_route_review::where('id',strip_tags($review_id))->first();
    }

    public function edit_route_review(Request $request) {
        if ($auth = PermissionService::authorize('routes_rewiew', 'edit')) return $auth;
        $review = Sport_route_review::where('id',strip_tags($request->review_id))->first();
        $data = $request->all();
        unset($data['_url']);

        // dd($data);

        $saved = $review->update($data); 
    }

    public function user_hide_review($review_id) {
        $review = Sport_route_review::where('id', $review_id)->where('user_id', auth()->id())->first();
        if (!$review) return response()->json(['error' => 'Not found'], 404);
        if ($review->admin_hidden) return response()->json(['error' => 'Admin hidden', 'admin_hidden' => true], 403);
        $review->published = 0;
        $review->save();
        return response()->json(['success' => true]);
    }

    public function user_show_review($review_id) {
        $review = Sport_route_review::where('id', $review_id)->where('user_id', auth()->id())->first();
        if (!$review) return response()->json(['error' => 'Not found'], 404);
        if ($review->admin_hidden) return response()->json(['error' => 'Hidden by admin', 'admin_hidden' => true], 403);
        $review->published = 1;
        $review->save();
        return response()->json(['success' => true]);
    }

    public function hide_route_review(Request $request, $review_id) {
        $review = Sport_route_review::find(strip_tags($review_id));
        if ($review) {
            $review->published = 0;
            $review->admin_hidden = 1;
            $review->hidden_reason = $request->input('hidden_reason');
            $review->hidden_reason_text = $request->input('hidden_reason_text');
            $review->save();
        }
        return response()->json(['success' => true]);
    }

    public function del_route_review($review_id) {
        $auth = PermissionService::authorize('routes_rewiew', 'del');
        if ($auth) return $auth;

        $review = Sport_route_review::where('id', strip_tags($review_id))->first();
        if ($review) {
            $review->delete();
        }
    }

    public function bulk_del_route_review(Request $request) {
        $auth = PermissionService::authorize('routes_rewiew', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        Sport_route_review::destroy($request->ids);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }
}

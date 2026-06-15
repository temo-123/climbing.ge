<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Guide\Sport_route_review;
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
        $review['ascent_style']= $request["ascent_style"];
        $review['text']        = $request["text"];
        $review['stars']       = $request["stars"];
        $review['user_id']     = $user->id;

        if (isset($request['climbed']) && $request['climbed']) {
            $review['climbed_data'] = $request["climbed_data"];
        }

        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback!',
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
}

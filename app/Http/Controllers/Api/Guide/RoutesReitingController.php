<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Guide\Sport_route_review;
// use App\Models\Guide\Sport_route_review_user;

use Auth;

class RoutesReitingController extends Controller
{
    public function get_all_review() {
        // return Sport_route_review::get();

        if(Sport_route_review::count() > 0){
            $all_reviews = Sport_route_review::get();

            $reviews = [];
            foreach ($all_reviews as $review) {
                $user = $review->user;
                
                array_push($reviews, [
                    'review' => $review, 
                    'route' => $review->route,
                    'user' => [
                        'name' => $user ? $user->name : '',
                        'surname' => $user ? $user->surname : '',
                        'email' => $user ? $user->email : '',
                    ],
                ]);
            }
            
            return $reviews;
        }
    }

    public function get_all_route_reviews(Request $request) {
        $rews = Sport_route_review::where('route_id', '=', $request->route_id)->get();
        foreach ($rews as $rew) {
            $rew -> user;
        }
        return $rews;
    }

    public function get_user_review() {
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
        // 
    }

    public function get_actyve_review($review_id){
        return Sport_route_review::where('id',strip_tags($review_id))->first();
    }

    public function edit_route_review(Request $request) {
        $review = Sport_route_review::where('id',strip_tags($request->review_id))->first();
        $data = $request->all();
        unset($data['_url']);

        // dd($data);

        $saved = $review->update($data); 
    }

    public function del_route_review(Request $request) {
        if ($request->isMethod('delete') && Auth::user()) {

            $review = Sport_route_review::where('id',strip_tags($request->review_id))->first();
            // $user_review_relation = Sport_route_review_user::where('user_id', '=', Auth::user()->id)->where('review_id', '=', $review->id)->first();

            // $user_review_relation -> delete();
            $review -> delete();
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Sport_route_review;
use App\Models\Sport_route_review_user;

use Auth;

class RoutesReitingController extends Controller
{
    function get_all_review() {
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

    function get_user_review() {
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

    function get_user_sport_routes_review() {
        if (Auth::user()) {
            $user_review = Sport_route_review_user::where('user_id', '=', Auth::user()->id)->get();

            $routes_reiting = [];

            foreach ($user_review as $rev) {
                array_push($routes_reiting, [
                    "review" => $rev->review,
                    "route" => $rev->review->route,
                ]);
            }
            // dd($routes_reiting);
            return $routes_reiting;
        }
    }

    function create_route_review(Request $request) {
        $review = new Sport_route_review;
        
        $review['route_id']=$request["route_id"];

        $review['text']=$request["text"];
        $review['stars']=$request["stars"];

        if (isset($request['climbed']) && $request['climbed']) {
            $review['climbed_data']=$request["climbed_data"];
        }

        $review->save();

        $this->create_user_review_relatione($review->id);
    }

    private function create_user_review_relatione($review_id){
        if (Auth::user()) {
            $review_user_rel = new Sport_route_review_user;
            $review_user_rel['review_id'] = $review_id;
            $review_user_rel['user_id'] = Auth::user()->id;
            $review_user_rel->save();
        }
    }

    function get_actyve_review(Request $request){
        return Sport_route_review::where('id',strip_tags($request->review_id))->first();
    }

    function edit_route_review(Request $request) {
        $review = Sport_route_review::where('id',strip_tags($request->review_id))->first();
        $data = $request->all();
        unset($data['_url']);

        // dd($data);

        $saved = $review->update($data); 
    }

    function del_route_review(Request $request) {
        if ($request->isMethod('delete') && Auth::user()) {

            $review = Sport_route_review::where('id',strip_tags($request->review_id))->first();
            $user_review_relation = Sport_route_review_user::where('user_id', '=', Auth::user()->id)->where('review_id', '=', $review->id)->first();

            $user_review_relation -> delete();
            $review -> delete();
        }
    }
}

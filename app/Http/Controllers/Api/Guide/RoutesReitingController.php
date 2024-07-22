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
                
                array_push($reviews, [
                    'review' => $review, 
                    'route' => $review->route,
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

    public function create_route_review(Request $request) {
        if (Auth::user()) {
            // $user_review_count = Sport_route_review::where('user_id', '=', Auth::user()->id)->where('route_id', '=', $request["route_id"])->count();
            $user_review_count = Auth::user()->sport_route_reviews->where('route_id', '=', $request["route_id"])->count();
            if($user_review_count == 0){
                $review = new Sport_route_review;
                
                $review['route_id']=$request["route_id"];
                $review['ascent_style']=$request["ascent_style"];

                $review['text']=$request["text"];
                $review['stars']=$request["stars"];

                $review['user_id'] = Auth::user()->id;

                if (isset($request['climbed']) && $request['climbed']) {
                    $review['climbed_data']=$request["climbed_data"];
                }

                $review->save();

                // $this->create_user_review_relatione($review->id);

                return 'Thank you for review!';
            }
            else{
                return "You already have review for this route, you don't can add more review for this route!";
            }
        }
        else{
            return "Ples login!";
        }
    }

    // private function create_user_review_relatione($review_id){
    //     if (Auth::user()) {
    //         $review_user_rel = new Sport_route_review_user;
    //         $review_user_rel['review_id'] = $review_id;
    //         $review_user_rel['user_id'] = Auth::user()->id;
    //         $review_user_rel->save();
    //     }
    // }

    public function get_actyve_review(Request $request){
        return Sport_route_review::where('id',strip_tags($request->review_id))->first();
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
            $user_review_relation = Sport_route_review_user::where('user_id', '=', Auth::user()->id)->where('review_id', '=', $review->id)->first();

            $user_review_relation -> delete();
            $review -> delete();
        }
    }
}

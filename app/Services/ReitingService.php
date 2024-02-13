<?php

namespace App\Services;

use Auth;

class ReitingService
{
    public static function get_reiting($user_review_model) {
        if (Auth::user()) {
            $user_review = $user_review_model::where('user_id', '=', Auth::user()->id)->get();

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

    function create_route_review($user_review_model, $request) {
        $review = new $user_review_model;
        
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
            $review_user_rel = new $user_review_model;
            $review_user_rel['review_id'] = $review_id;
            $review_user_rel['user_id'] = Auth::user()->id;
            $review_user_rel->save();
        }
    }

    public static function del_reiting($user_review_model, $review_model, $request) {
        if ($request->isMethod('delete') && Auth::user()) {

            $review = $review_model::where('id',strip_tags($request->review_id))->first();
            $user_review_relation = $user_review_model::where('user_id', '=', Auth::user()->id)->where('review_id', '=', $review->id)->first();

            $user_review_relation -> delete();
            $review -> delete();
        }
    }
}

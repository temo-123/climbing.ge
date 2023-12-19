<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SportRouteReview;

class RoutesReitingController extends Controller
{
    function get_reitings() {
        
    }

    function get_route_reitin() {
        
    }

    function create_route_review(Request $request) {
        $review = new SportRouteReview;
        
        $review['route_id']=$request["route_id"];

        $review['text']=$request["text"];
        $review['stars']=$request["stars"];

        if (isset($request['climbed']) && $request['climbed']) {
            $review['climbed_data']=$request["climbed_data"];
        }

        $review->save();
    }

    function del_route_reitin() {
        
    }
}

<?php

namespace App\Http\Controllers\Shop\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Favorite_product;

use Auth;

class PrioritiesController extends Controller
{
    public function favorite_product(Request $request)
    {
        if ($request->actions == "add") {
            $input_events_id = $request->product_id;
            $input_user_id = Auth::user()->id;
            $events_id = intval($input_events_id);
            $user_id = intval($input_user_id);
            
            $input = Favorite_product::create([
                'product_id'=> $events_id,
                'user_id'=> $user_id,
            ]);
            
            return back()->with('status','This event added to your priority events list.');
    	}
    	elseif ($request->actions == "del") {
            $interested_events = Favorite_product::where("product_id","=",$request->product_id)->where ("user_id","=",Auth::user() -> id);
            $interested_events->delete();
            return back()->with('status','This event deleted from your priority events list.');
    	}
    }
}

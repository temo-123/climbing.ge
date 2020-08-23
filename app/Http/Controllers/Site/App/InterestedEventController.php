<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Interested_Event;

class InterestedEventController extends Controller
{
    public function events_interes(Request $request, $events_id)
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin']);

    	if ($request->actions == "add") {
            $input_events_id = $request -> events_id;
            $input_user_id = Auth::user() -> id;

            $input = Interested_Event::create([
                'article_id'=> $input_events_id,
                'user_id'=> $input_user_id,
            ]);
            return back()->with('status','This event added to your priority events list.');
        }
    	elseif ($request->actions == "del") {
            $interested_events = Interested_Event::where("article_id","=",$events_id)->where ("user_id","=",Auth::user() -> id);
            $interested_events->delete();
            return back()->with('status','This event deleted from your priority events list.');
    	}
    }

    public function favorite_product(Request $request, $product_id)
    {
    	if ($request->actions == "add") {
            $input_events_id = $request -> product_id;
            $input_user_id = Auth::user() -> id;

            $input = Favorite_product::create([
                'product_id'=> $input_events_id,
                'user_id'=> $input_user_id,
            ]);
            return back()->with('status','This event added to your priority events list.');
    	}
    	elseif ($request->actions == "del") {
            $interested_events = Favorite_product::where("product_id","=",$product_id)->where ("user_id","=",Auth::user() -> id);
            $interested_events->delete();
            return back()->with('status','This event deleted from your priority events list.');
    	}
    }
}

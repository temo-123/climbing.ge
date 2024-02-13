<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shiped_country;

class ShipedCountryController extends Controller
{
    public function get_countries(){
        return Shiped_country::get();
    }
    public function get_activ_country(Request $request)
    {
        return Shiped_country::where('id',strip_tags($request->country_id))->first();
    }

    public function add_country(Request $request)
    {   
        $adding_item = new Shiped_country;

        $adding_item['country'] = $request['adding_data']['country'];
        $adding_item['shiping_price'] = $request['adding_data']['shiping_price'];
        $adding_item['free_shiping_price_after'] = $request['adding_data']['free_shiping_price_after'];

        $adding_item->save();
    }

    public function edit_country(Request $request)
    {
        $editing_item = Shiped_country::where('id',strip_tags($request->country_id))->first();
        
        $editing_item['country'] = $request['editing_data']['country'];
        $editing_item['shiping_price'] = $request['editing_data']['shiping_price'];
        $editing_item['free_shiping_price_after'] = $request['editing_data']['free_shiping_price_after'];

        $editing_item->update();
    }

    public function del_country(Request $request)
    {
        $deliting_item = Shiped_country::where('id',strip_tags($request->country_id))->first();
        $deliting_item ->delete();
    }
}

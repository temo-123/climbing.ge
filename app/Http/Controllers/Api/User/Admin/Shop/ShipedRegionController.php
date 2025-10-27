<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Shiped_region;

class ShipedRegionController extends Controller
{
    public function get_all_shiped_regions(){
        return Shiped_region::get();
    }
    public function get_activ_region(Request $request)
    {
        return Shiped_region::where('id',strip_tags($request->region_id))->first();
    }

    public function add_region(Request $request)
    {   
        $adding_item = new Shiped_region;

        $adding_item['region'] = $request['adding_data']['region'];
        $adding_item['shiping_price'] = $request['adding_data']['shiping_price'];
        $adding_item['free_shiping_price_after'] = $request['adding_data']['free_shiping_price_after'];

        $adding_item->save();
    }

    public function edit_region(Request $request)
    {
        $editing_item = Shiped_region::where('id',strip_tags($request->region_id))->first();
        
        $editing_item['region'] = $request['editing_data']['region'];
        $editing_item['shiping_price'] = $request['editing_data']['shiping_price'];
        $editing_item['free_shiping_price_after'] = $request['editing_data']['free_shiping_price_after'];

        $editing_item->update();
    }

    public function del_region(Request $request)
    {
        $deliting_item = Shiped_region::where('id',strip_tags($request->region_id))->first();
        $deliting_item ->delete();
    }
}

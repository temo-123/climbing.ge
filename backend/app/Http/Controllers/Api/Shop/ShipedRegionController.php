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

}

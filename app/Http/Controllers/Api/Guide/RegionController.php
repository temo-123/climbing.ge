<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Region;
use App\Models\Guide\Article_region;

class RegionController extends Controller
{
    public function get_all_outdoor_regions()
    {
        return Region::get();
    }

    public function get_local_regions(Request $request)
    {
        $data = [];
        if($request -> lang == 'ka'){
            $regions = Region::select('id', 'ka_name', 'ka_text')->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ka_name, 'text'=>$region->ka_text]);
            }
        }
        else{
            $regions = Region::select('id', 'us_name', 'us_text')->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->us_name, 'text'=>$region->us_text]);
            }
        }

        return $data;
    }
    public function get_local_region(Request $request)
    {
        $data = [];

        if($request -> lang == 'ka'){
            $regions = Region::select('id', 'ka_name', 'ka_text', 'map')->where('id',strip_tags($request->region_id))->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->ka_name, 'text'=>$region->ka_text, "map"=>$region->map]);
            }
        }
        else{
            $regions = Region::select('id', 'us_name', 'us_text', 'map')->where('id',strip_tags($request->region_id))->get();

            foreach ($regions as $region) {
                array_push($data, ['id'=>$region->id,'name'=>$region->us_name, 'text'=>$region->us_text, "map"=>$region->map]);
            }
        }

        return $data;
    }
}

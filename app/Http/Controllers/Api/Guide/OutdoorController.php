<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Services\ArticlesService;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

use App\Models\Guide\Region;
use App\Models\Guide\Article_region;
class OutdoorController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_all()
    {
        return Region::get();
    }
    
    public function get_spots_by_regions(Request $request)
    {
        // $regions_data = [];
        if($request -> lang == 'ka'){
            $regions = Region::select('id', 'ka_name', 'ka_text')->get();

            // foreach ($regions as $region) {
            //     array_push($regions_data, ['id'=>$region->id,'name'=>$region->ka_name, 'text'=>$region->ka_text]);
            // }
        }
        else{
            $regions = Region::select('id', 'us_name', 'us_text')->get();

            // foreach ($regions as $region) {
            //     array_push($regions_data, ['id'=>$region->id,'name'=>$region->us_name, 'text'=>$region->us_text]);
            // }
        }
        $regions_array = [];

        foreach($regions as $region){
            $global_article = $region->articles;
            // dd($global_article->count());
            if($global_article->count() > 0){
                array_push($regions_array, ['region' => $region, 'spots' => $this->get_outdoor_data($request -> lang, $global_article)]);
            }
        }

        $all_outdoors = json_decode(json_encode(Article::where('category', '=', 'outdoor')->where('published', '=', '1')->get()), true);
        $reg_array = [];
        
        foreach ($regions_array as $region) {
            foreach ($region['spots'] as $spot) {
                array_push($reg_array, $spot['area']['global_data']);

                $reg_array = json_decode(json_encode($reg_array), true);
            }
        }
        $results = array_diff(array_column($all_outdoors, 'id'), array_column($reg_array, 'id'));
        
        $other_articles = [];
        foreach($results as $result){
            $other_article = Article::where('id', '=', $result)->where('published', '=', '1')->get();
            if($other_article->count()){
                $art = $this->get_outdoor_data($request -> lang, $other_article);
                array_push($other_articles, $art[0]);
            }
        }
        if(count($other_articles)){
            array_push($regions_array, ['region' => ['name' => 'other'], 'spots' => $other_articles]);
        }

        // dd($regions_array);

        return $regions_array;
    }

    function get_outdoor_data($lang, $global_outdoors) {
        $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $lang);

        $route_num = 0;
        $mtp_num = 0;
        $route_quantity = array();

        $area_data = [];

        foreach($global_outdoors as $outdoor){
            $outdoor -> outdoor_region;
            $sector_n = Sector::where('article_id', '=', $outdoor->id)->get();
            $routes_a = array ($outdoor->title);
            $boulder_routes = array ($outdoor->title);
            $mtps_a = array ();
            $sector_count = Sector::where('article_id', '=', $outdoor->id)->count();
            foreach($sector_n as $sector){
                $routes = Route::where('sector_id', '=', $sector->id)->count();
                foreach((array) $routes as $route){
                    $route_num++;
                    array_push($routes_a, $route);
                }
                $mtps = MTP::where('sector_id', '=', $sector->id)->count();
                if ($mtps > 0) {
                    foreach((array) $mtps as $mtp){
                        $mtp_num++;
                        array_push($mtps_a, $mtp);
                    }
                }
            }
            if($route_num == $sector_count) {
                $route_sum=array_sum($routes_a);
                $mtp_sum=array_sum($mtps_a);
                array_push($route_quantity, array("article_id" => $outdoor->id, "sectors" => $sector_count, "routes" => $route_sum, "mtps" => $mtp_sum) ); // push route num in last array
            }
            else $route_sum=0;{
                $route_num = 0;
            }
        }

        // dd($outdoors, $route_quantity);
        
        foreach ($outdoors as $outdoor) {
            foreach ($route_quantity as $quantity) {
                if ($quantity['article_id'] == $outdoor['global_data']['id']) {
                    array_push($area_data, ["route_quantyty" => $quantity, "area" => $outdoor]);
                }
            }
        }

        // dd($area_data[0]['area']['global_data']['outdoor_region']);
        return $area_data;
    }

    public function locale_regions(Request $request)
    {
        $data = [];
        // if($request -> lang == 'ru'){
        //     $regions = Region::select('id', 'ru_name', 'ru_text')->get();

        //     foreach ($regions as $region) {
        //         array_push($data, ['id'=>$region->id,'name'=>$region->ru_name, 'text'=>$region->ru_text]);
        //     }
        // }
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


    public function locale_region(Request $request)
    {
        // dd($request->lang, $request->region_id);

        $data = [];
        // if($request -> lang == 'ru'){
        //     $regions = Region::select('id', 'ru_name', 'ru_text', 'map')->where('id',strip_tags($request->region_id))->get();

        //     foreach ($regions as $region) {
        //         array_push($data, ['id'=>$region->id,'name'=>$region->ru_name, 'text'=>$region->ru_text, "map"=>$region->map]);
        //     }
        // }
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

    public function get_filtred_outdoor_spots_for_admin(Request $request)
    {
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_region;
        }
    }

    public function get_filtred_outdoor_spots_for_gest(Request $request)
    {
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;

            $global_outdoors = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1);
            $article_count = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1)->count();

            $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $request->lang);

            $route_num = 0;
            $mtp_num = 0;
            $route_quantity = array();

            $area_data = [];

            foreach($global_outdoors as $outdoor){
                $sector_n = Sector::where('article_id', '=', $outdoor->id)->get();
                $routes_a = array ($outdoor->title);
                $boulder_routes = array ($outdoor->title);
                $mtps_a = array ();
                $sector_count = Sector::where('article_id', '=', $outdoor->id)->count();
                foreach($sector_n as $sector){
                    $routes = Route::where('sector_id', '=', $sector->id)->count();
                    foreach((array) $routes as $route){
                        $route_num++;
                        array_push($routes_a, $route);
                    }
                    $mtps = MTP::where('sector_id', '=', $sector->id)->count();
                    if ($mtps > 0) {
                        foreach((array) $mtps as $mtp){
                            $mtp_num++;
                            array_push($mtps_a, $mtp);
                        }
                    }
                }
                if($route_num == $sector_count) {
                    $route_sum=array_sum($routes_a);
                    $mtp_sum=array_sum($mtps_a);
                    array_push($route_quantity, array("article_id" => $outdoor->id, "sectors" => $sector_count, "routes" => $route_sum, "mtps" => $mtp_sum) ); // push route num in last array
                }
                else $route_sum=0;{
                    $route_num = 0;
                }
            }
        }
        
        foreach ($outdoors as $outdoor) {
            foreach ($route_quantity as $quantity) {
                if ($quantity['article_id'] == $outdoor['global_data']['id']) {
                    array_push($area_data, ["route_quantyty" => $quantity, "area" => $outdoor]);
                }
            }
        }

        return $area_data;
    }

    public function add_spot(Request $request)
    {
        $validate = $this->region_validate($request->data);

        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $spot = new Region;

            $spot['us_name'] = $request->data['us_name'];
            // $spot['ru_name'] = $request->data['ru_name'];
            $spot['ka_name'] = $request->data['ka_name'];

            $spot['us_text'] = $request->data['us_text'];
            // $spot['ru_text'] = $request->data['ru_text'];
            $spot['ka_text'] = $request->data['ka_text'];

            $spot['map'] = $request->data['map'];

            $spot -> save();
        }
    }

    public function get_editing_spot_data(Request $request)
    {
        return Region::where('id',strip_tags($request->id))->first();
    }

    public function edit_spot(Request $request)
    {
        $validate = $this->region_validate($request->data);


        if ($validate != null) {
            return response()->json([
                'validation' => $validate
            ], 422);
        }
        else{
            $editing_region = Region::where('id',strip_tags($request->id))->first();

            $editing_region['us_name'] = $request->data['us_name'];
            // $editing_region['ru_name'] = $request->data['ru_name'];
            $editing_region['ka_name'] = $request->data['ka_name'];

            $editing_region['us_text'] = $request->data['us_text'];
            // $editing_region['ru_text'] = $request->data['ru_text'];
            $editing_region['ka_text'] = $request->data['ka_text'];

            $editing_region['map'] = $request->data['map'];

            $editing_region -> save();
        }
    }

    public function del_spot(Request $request)
    {
        $region = Region::where('id',strip_tags($request->id))->first();
        $region -> delete();
    }

    public function region_validate($data)
    {
        $validator = Validator::make($data, [
            'us_name' => 'required',
            // 'ru_name' => 'required',
            'ka_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}

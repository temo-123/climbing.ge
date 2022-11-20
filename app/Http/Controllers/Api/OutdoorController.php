<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Locale_article;

use App\Models\Region;
use App\Models\Article_region;

use App\Services\GetArticlesService;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

use App\Models\Favorite_outdoor_area;

use Auth;

class OutdoorController extends Controller
{
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

            $outdoors = GetArticlesService::get_locale_article_use_locale($global_outdoors, $request->lang);

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
                if ($quantity['article_id'] == $outdoor['id']) {
                    array_push($area_data, ["route_quantyty" => $quantity, "area" => $outdoor]);
                }
            }
        }

        return $area_data;
    }

    public function add_spot(Request $request)
    {
        // dd( $request->data['us_name']);

        $spot = new Region;

        $spot['us_name'] = $request->data['us_name'];
        $spot['ru_name'] = $request->data['ru_name'];
        $spot['ka_name'] = $request->data['ka_name'];

        $spot['us_text'] = $request->data['us_text'];
        $spot['ru_text'] = $request->data['ru_text'];
        $spot['ka_text'] = $request->data['ka_text'];

        $spot['map'] = $request->data['map'];

        $spot -> save();
    }

    public function get_editing_spot_data(Request $request)
    {
        return Region::where('id',strip_tags($request->id))->first();
    }

    public function edit_spot(Request $request)
    {
        $editing_region = Region::where('id',strip_tags($request->id))->first();

        $editing_region['us_name'] = $request->data['us_name'];
        $editing_region['ru_name'] = $request->data['ru_name'];
        $editing_region['ka_name'] = $request->data['ka_name'];

        $editing_region['us_text'] = $request->data['us_text'];
        $editing_region['ru_text'] = $request->data['ru_text'];
        $editing_region['ka_text'] = $request->data['ka_text'];

        $editing_region['map'] = $request->data['map'];

        $editing_region -> save();
    }

    public function del_spot(Request $request)
    {
        $region = Region::where('id',strip_tags($request->id))->first();
        $region -> delete();
    }

    public function get_faworite_outdoor_region(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->get();
            $articles = [];
            foreach ($fav_area as $area) {
                $global_articles = Article::where('id', '=', $area->article_id)->get();
                $outdoors = GetArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
                array_push($articles, $outdoors[0]);
            }
            
            return $articles;
        }
        else{
            return 'Plees login!';
        }
    }

    public function del_faworite_outdoor_region(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->first();
            $fav_area ->delete();
        }
        else{
            return 'Plees login!';
        }
    }
}

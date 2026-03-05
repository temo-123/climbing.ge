<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

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
    public function get_filtred_outdoor_spots(Request $request)
    {        
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;

            $global_outdoors = $filtred_articles_by_region->where('category', '=', 'outdoor');
            // $global_outdoors = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1);
            // $article_count = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1)->count();

            if($request->published == 1){
                $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors->where('published', '=', 1), $request->lang);
            }
            else if($request->published == 0){
                $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors->where('published', '=', 0), $request->lang);
            }
            else{
                $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $request->lang);
            }

            // $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $request->lang);

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

    public function get_filtred_outdoor_spots_for_admin(Request $request)
    {
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_region;
        }
    }
}

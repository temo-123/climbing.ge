<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;
use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Services\ArticlesService;

use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

use App\Models\Guide\Region;
use App\Models\Guide\Article_region;
class OutdoorController extends Controller
{
    public function get_filtred_outdoor_spots(Request $request)
    {
        if ($auth = PermissionService::authorize('article', 'show')) return $auth;

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

            // Admin/permission-gated — sees TOTAL counts regardless of each
            // Sector's own publish state (see
            // ArticlesService::get_route_quantity_for_outdoors docblock),
            // unlike the public-facing controller's equivalent method.
            $route_quantity = ArticlesService::get_route_quantity_for_outdoors($global_outdoors, false);

            $area_data = [];
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
        if ($auth = PermissionService::authorize('article', 'show')) return $auth;

        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;
            return $filtred_articles_by_region;
        }
    }
}

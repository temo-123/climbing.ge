<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Services\ArticlesService;

use App\Models\Guide\Favorite_outdoor_area;
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

use App\Models\Guide\Region;
use App\Models\Guide\Article_region;
use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use Illuminate\Support\Facades\Cache;
class OutdoorController extends Controller
{    
    public function get_spots_by_regions(Request $request)
    {
        $regions = $this->get_region_data($request -> lang);
        
        $regions_array = [];

        foreach($regions as $region){
            $global_article = $region->articles->where('category', '=', 'outdoor')->where('published', '=', '1');
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

        return $regions_array;
    }

    function get_outdoor_data($lang, $global_outdoors) {
        $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $lang);

        foreach ($global_outdoors as $outdoor) { $outdoor -> outdoor_region; }

        // Guest/public-facing — only counts published Sectors (see
        // ArticlesService::get_route_quantity_for_outdoors docblock).
        $route_quantity = ArticlesService::get_route_quantity_for_outdoors($global_outdoors, true);

        $area_data = [];

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

    private function get_region_data($locale) {
        if ($locale == "us" || $locale == 'en') {
            $locale = "us";
        }

        $regions = Region::select('id', 'map', $locale . '_text', $locale . '_name')->get();

        foreach ($regions as $region) {
            $region['text'] = $region[$locale . '_text'];
            unset($region[$locale . '_text']);

            $region['name'] = $region[$locale . '_name'];
            unset($region[$locale . '_name']);
        }

        return $regions;
    }

    public function locale_regions(Request $request)
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


    public function locale_region(Request $request)
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

    // public function get_filtred_outdoor_spots_for_admin(Request $request)
    // {
    //     $region_article_count = Region::where('id', '=', $request->filter_id)->count();
    //     if($region_article_count > 0){
    //         $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;
    //         return $filtred_articles_by_region;
    //     }
    // }

    /**
     * One summary per published outdoor area for the "Crag for today" picker
     * on the outdoor list: route counts per grade band, sun / shade of its
     * sectors, family friendliness and the longest approach. Published
     * sectors only (same rule as the route counts). Cached for an hour.
     */
    public function get_area_profiles()
    {
        return Cache::remember('outdoor_area_profiles', 3600, function () {
            $articles = Article::where('category', 'outdoor')->where('published', 1)->pluck('id');
            $sectors = Sector::whereIn('article_id', $articles)->where('published', 1)->get();
            $routes = Route::whereIn('sector_id', $sectors->pluck('id'))->get(['id', 'sector_id', 'grade', 'category']);

            $band = function ($grade) {
                if (!preg_match('/^([3-9])([abc])?(\+)?$/i', trim((string) $grade), $m)) return 'project';
                $rank = (int) $m[1] * 10 + (isset($m[2]) && $m[2] !== '' ? ['a' => 0, 'b' => 2, 'c' => 4][strtolower($m[2])] : 0) + (isset($m[3]) ? 1 : 0);
                if ($rank <= 55) return 'easy';
                if ($rank <= 63) return 'mid';
                if ($rank <= 71) return 'hard';
                return 'expert';
            };

            return $articles->map(function ($article_id) use ($sectors, $routes, $band) {
                $own = $sectors->where('article_id', $article_id);
                $own_routes = $routes->whereIn('sector_id', $own->pluck('id'));
                $bands = ['easy' => 0, 'mid' => 0, 'hard' => 0, 'expert' => 0, 'project' => 0];
                foreach ($own_routes as $route) $bands[$band($route->grade)]++;

                // "10 - 15" → 15: the longest figure in each sector's walking time
                $minutes = $own->map(fn ($s) => preg_match_all('/\d+/', (string) $s->wolking_time, $m) ? max(array_map('intval', $m[0])) : null)->filter();

                return [
                    'article_id'   => $article_id,
                    'routes'       => $own_routes->count(),
                    'bands'        => $bands,
                    'sun'          => $own->contains(fn ($s) => $s->all_day_in_sun),
                    'shade'        => $own->contains(fn ($s) => $s->all_day_in_shade || $s->in_the_shade_befornoon || $s->in_the_shade_afternoon || $s->in_shade_after_10 || $s->in_shade_after_15),
                    'morning_shade'   => $own->contains(fn ($s) => $s->in_the_shade_befornoon),
                    'afternoon_shade' => $own->contains(fn ($s) => $s->in_the_shade_afternoon || $s->in_shade_after_15 || $s->in_shade_after_10),
                    'family'       => $own->contains(fn ($s) => $s->for_family || $s->for_kids),
                    'max_walk'     => $minutes->max(),
                ];
            })->filter(fn ($p) => $p['routes'] > 0)->values();
        });
    }

    public function get_filtred_outdoor_spots_for_gest(Request $request)
    {
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        // unknown / stale ?region= id (e.g. an old shared link): empty list, not a 500
        if($region_article_count == 0){
            return [];
        }
        if($region_article_count > 0){
            $filtred_articles_by_region = Region::where('id', '=', $request->filter_id)->first()->articles;

            $global_outdoors = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1);
            $article_count = $filtred_articles_by_region->where('category', '=', 'outdoor')->where('published', '=', 1)->count();

            $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $request->lang);

            // Explicitly the guest-facing endpoint — only counts published
            // Sectors (see ArticlesService::get_route_quantity_for_outdoors).
            $route_quantity = ArticlesService::get_route_quantity_for_outdoors($global_outdoors, true);

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


    public function get_filtred_outdoor_spots(Request $request)
    {
        $region_article_count = Region::where('id', '=', $request->filter_id)->count();
        // unknown / stale ?region= id (e.g. an old shared link): empty list, not a 500
        if($region_article_count == 0){
            return [];
        }
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

            // This method is reachable on the public/unauthenticated guide
            // routes (get_guide_routes.php), even though it also supports an
            // admin-style `published` article filter above — so the sector/
            // route/mtp counts must stay public-only regardless of which
            // articles were requested, or a guest could see counts that
            // include unpublished sectors.
            $route_quantity = ArticlesService::get_route_quantity_for_outdoors($global_outdoors, true);

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
}

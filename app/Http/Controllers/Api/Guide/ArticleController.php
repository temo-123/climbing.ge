<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;
use App\Services\ArticlesService;
use App\Services\GeneralInfoService;

use App\Models\Guide\Article_image;

use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;
use App\Models\Guide\Comment;

use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\Mount_route_image;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Models\Guide\Mount;
use App\Models\Guide\Locale_mount;

use App\Models\Guide\General_info_article;
use App\Models\Guide\General_info;

use App\Models\Guide\Region;

use App\Models\Guide\Article_mount;
use App\Models\Guide\Article_region;

use App\Models\Guide\Suport_local_bisnes;

use Auth;
use File;
use Validator;

class ArticleController extends Controller
{
    public function get_category_articles(Request $request)
    {
        return Article::latest('id')->where('category', '=', $request->category)->get();
    }


    public function get_articles_for_bisnes_suport(Request $request)
    {
        return Article::select('id', 'category', 'url_title')->latest('id')->
                        where('category', '!=', 'news')->
                        where('category', '!=', 'partner')->
                        where('category', '!=', 'special_articles')->
            get();
    }

    public function get_article_for_bisnes_page(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('url_title', '=', $request->bisnes_url_title)->first();


        $article = $bisnes->articles->first();

        if($article->count() > 0){
            $global_articles = Article::where('id', '=', $article->id)->where('published', '=', 1)->get();
            return ArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
        }
    }

    public function get_similar_locale_article(Request $request)
    {
        $articles = [];
        
        if ($request->article_category == "outdoor") {
            $articles = $this->similar_outdoor_list($request->lang, $request->article_id);
        }
        else {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }

        return $articles;
    }

    public function similar_article_list($category, $lang, $article_id)
    {
        $all_articles = Article::where('category', '=', $category)->where('id', '!=', $article_id)->where('published', '=', 1)->get();
        $articles = [];
        if($all_articles->count() > 0){
            if($all_articles->count() >= 4){
                $global_articles = $all_articles->random(4);
            }
            else{
                $global_articles = $all_articles->random($all_articles->count());
            }
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }

    public function similar_outdoor_list($lang, $article_id = 0)
    {
        if($article_id == 0){
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->get()->random(4);
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
        }
        else{
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->get()->random(4);
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->count();
        }

        $outdoors = ArticlesService::get_locale_article_use_locale($global_outdoors, $lang);

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
        
        foreach ($outdoors as $outdoor) {
            foreach ($route_quantity as $quantity) {
                if ($quantity['article_id'] == $outdoor['global_data']['id']) {
                    array_push($area_data, ["route_quantyty" => $quantity, "area" => $outdoor]);
                }
            }
        }

        // dd($area_data);
        return $area_data;
    }


    /**
     * Next functions get locale articles list.
     *
     * 
    */

    public function get_locale_articles(Request $request)
    {   
        $articles = [];
        if($request->category == "news") {
            $articles = $this->article_list($request->category, $request->lang);
            array_splice($articles, 7);
        }
        else {
            $articles = $this->article_list($request->category, $request->lang);
        }

        return $articles;
    }

    public function article_list($category, $lang)
    {
        // dd($category, $lang);
        $article_count = Article::where('category', '=', $category)->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::latest('id')->where('category', '=', $category)->where('published', '=', 1)->get();
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        // dd($articles);
        return $articles;
    }


    /**
     * Next functions get locale article on article page.
     *
     * 
    */

    public function get_locale_article_on_page(Request $request)
    {
        $article = [];

        $global_article_count = Article::where('url_title',strip_tags($request->url_title))
                                                                ->where(
                                                                function($query) {
                                                                    return $query
                                                                            ->where('published', '=', 2)
                                                                            ->orWhere('published', '=', 1);
                                                                    })
                                                                ->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($request->url_title))
                                                                ->where(
                                                                function($query) {
                                                                    return $query
                                                                            ->where('published', '=', 2)
                                                                            ->orWhere('published', '=', 1);
                                                                    })
                                                                ->first();
            $article = ArticlesService::get_locale_article_in_page($global_article, $request->lang);

            return $article;
        }
        else{
            return abort(404);
        }
    }
}
<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// use App\Services\ImageEditService;
use App\Services\ImageControllService;
use App\Services\URLTitleService;
use App\Services\ArticlesService;
use App\Services\GeneralInfoService;

use App\Models\Article;
use App\Models\Locale_article;
use App\Models\Comment;

use App\Models\Spot_rocks_image;
use App\Models\Mount_route_image;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

use App\Models\Mount;
use App\Models\Locale_mount;

use App\Models\General_info_article;
use App\Models\General_info;

use App\Models\Region;

use App\Models\Article_mount;
use App\Models\Article_region;

use App\Models\Suport_local_bisnes;
// use App\Models\Favorite_outdoor_area;

use Auth;
use File;
use Validator;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::get();
    }


    public function get_articles_for_forum(Request $request)
    {
        $global_news = Article::where('category', '=', $request->category)->where("published", "=", 1)->get();
        $news = ArticlesService::get_locale_article_use_locale($global_news, $request->locale);
        return $news;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Article::latest('id')->where('category', '=', $request->category)->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($url_title)
    {
        $global_news = Article::where('url_title',strip_tags($url_title))->where('published', '=', 1)->first();
        $news = ArticlesService::get_locale_article_in_page($global_news);

        return $news[0];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $image_path = $data['global_article']['category'].'_img/';

        $article_adding = ArticlesService::edit_content($data, Article::class, Locale_article::class, '_article', $request, $image_path);

        if (!array_key_exists('validation', $article_adding->original)) {
            GeneralInfoService::edit_general_info_relatione($global_blocks, $article_adding->original[0], 'article');

            $this->description_img($article_adding->original[0], $global_blocks, $data, $request);
        }
        else{
            return $article_adding;
        }
    }

    public function add_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );
        
        $image_path = $data['global_article']['category'].'_img/';

        $article_editing = ArticlesService::add_content($data, Article::class, Locale_article::class, '_article', $image_path, $request);

        if (!array_key_exists('validation', $article_editing->original)) {
            GeneralInfoService::add_general_info_relatione($global_blocks, $article_editing->original[0], 'article');

            $this->description_img($article_editing->original[0], $global_blocks, $data, $request);
        }
        else {
            return $article_editing;
        }
        
    }


    private function description_img($actyve_id, $global_blocks, $data, $request) 
    {
        if($data['global_article']["category"] == 'outdoor'){
            if($request->hasFile('outdoor_area_images')){
                $this->add_outdoor_area_images($request->outdoor_area_images, $actyve_id);
            }
        }
        else if($data['global_article']["category"] == 'mount_route'){
            if($request->hasFile('mount_route_images')){
                $this->add_mount_route_images($request->mount_route_images, $actyve_id);
            }
        }

        if($data['global_article']["category"] == 'outdoor'){
            if(isset($data['global_article']['region_id'])){
                $article_region = Article_region::where('article_id', '=', $actyve_id)->first();
                if($article_region){
                    if($article_region['region_id'] != $data['global_article']['region_id']){
                        $article_region['article_id'] = $actyve_id;
                        $article_region['region_id'] = $data['global_article']['region_id'];
                        $article_region -> save();
                    }
                }
                else{
                    $new_mount = new Article_region;
                    $new_mount['article_id'] = $actyve_id;
                    $new_mount['region_id'] = $data['global_article']['region_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($data['global_article']['region_id'])){
                $deliting_article_region = Article_region::where('article_id', '=', $actyve_id)->first();
                if($deliting_article_region){
                    $deliting_article_region -> delete();
                }
            }
        }
        
        if($data['global_article']["category"] == 'mount_route'){
            if(isset($data['global_article']['mount_id'])){
                $article_mount = Article_mount::where('article_id', '=', $actyve_id)->first();
                if($article_mount){
                    if($article_mount['mount_id'] != $data['global_article']['mount_id']){
                        $article_mount['article_id'] = $actyve_id;
                        $article_mount['mount_id'] = $data['global_article']['mount_id'];
                        $article_mount -> save();
                    }
                }
                else{
                    $new_mount = new Article_mount;
                    $new_mount['article_id'] = $actyve_id;
                    $new_mount['mount_id'] = $data['global_article']['mount_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($data['global_article']['mount_id'])){
                $deliting_article_mount = Article_mount::where('article_id', '=', $actyve_id)->first();
                if($deliting_article_mount){
                    $deliting_article_mount -> delete();
                }
            }
        }
    }


    // public function images_array_validate($images)
    // {
    //     // 
    // }

    public function add_outdoor_area_images($images, $article_id)
    {
        foreach ($images as $image) {
            $file_new_name = ImageControllService::upload_loop_image('images/spot_rocks_img/', $image);

            if(file_exists(public_path('images/spot_rocks_img/') . '/' . $file_new_name)){
                $add_outdoor_area_image = new Spot_rocks_image;
        
                $add_outdoor_area_image['image'] = $file_new_name;
                $add_outdoor_area_image['article_id'] = $article_id;
        
                $saiving = $add_outdoor_area_image -> save();

                if($saiving){
                    echo 'Spot rocks image Upload socsesful';
                }
            }
            else{
                // return 'Upload error';
                echo 'Spot rocks image Upload error';
            }
        }
    }

    public function get_article_on_bisnes_page(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('url_title', '=', $request->bisnes_url_title)->first();

        $article_count =Article::where('id', '=', $bisnes->article_id)->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('id', '=', $bisnes->article_id)->where('published', '=', 1)->get();
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
        }
        return $articles;
    }

    public function add_mount_route_images($images, $article_id)
    {
        foreach ($images as $image) {
            $file_new_name = ImageControllService::upload_loop_image('images/mount_route_description_img/', $image);

            if(file_exists(public_path('images/mount_route_description_img/') . '/' . $file_new_name)){
                $add_mount_image = new Mount_route_image;
        
                $add_mount_image['image'] = $file_new_name;
                $add_mount_image['article_id'] = $article_id;
        
                $saiving = $add_mount_image -> save();

                if($saiving){
                    echo 'Mount route Upload socsesful';
                }
            }
            else{
                // return 'Upload error';
                echo 'Mount route Upload error';
            }
        }
    }


    public function add_regioin_articles($region_id, $article_id){
        $article = new Article_region;
        
        $article['region_id'] = $region_id;
        $article['article_id'] = $article_id;

        $article -> save();
    }

    public function add_mount_articles($mount_id, $article_id){
        $article = new Article_mount;
        
        $article['mount_id'] = $mount_id;
        $article['article_id'] = $article_id;

        $article -> save();
    }





    public function get_similar_locale_article(Request $request)
    {
        // dd($request->article_id, $request->article_category);
        $articles = [];
        
        if ($request->article_category == "outdoor") {
            $articles = $this->similar_outdoor_list($request->lang, $request->article_id);
        }
        elseif ($request->article_category == "indoor") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "ice") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "news") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "other") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "tech_tip" || $request->article_category == 'security') {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "partners") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "event") {
            $articles = $this->similar_article_list($request->article_category, $request->lang, $request->article_id);
        }
        elseif ($request->article_category == "mount_route") {
            $articles = $this->similar_mount_route_list($request->lang, $request->article_id);
        }

        return $articles;
    }


    public function similar_article_list($category, $lang, $article_id)
    {
        $article_count = Article::where('category', '=', $category)->where('id', '!=', $article_id)->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', $category)->where('id', '!=', $article_id)->where('published', '=', 1)->simplePaginate(4);
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }


    public function similar_mount_route_list($lang, $article_id)
    {
        $article_count = Article::where('category', '=', 'mount_route')->where('id', '!=', $article_id)->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', 'mount_route')->where('id', '!=', $article_id)->where('published', '=', 1)->simplePaginate(4);
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }


    public function similar_outdoor_list($lang, $article_id = 0)
    {
        if($article_id == 0){
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->simplePaginate(4);
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
        }
        else{
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->simplePaginate(4);
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
        // dd($request->category);
        if ($request->category == "outdoor") {
            $articles = $this->outdoor_list($request->lang);
        }
        elseif ($request->category == "indoor") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "ice") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "news") {
            $articles = $this->news_list($request->lang);
        }
        elseif ($request->category == "other") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "tech_tip" || $request->article_category == 'security') {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "partners") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "event") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "mount_route") {
            // $articles = $this->mount_route_list($request->lang);
            $articles = $this->article_list($request->category, $request->lang);
        }

        return $articles;
    }


    public function outdoor_list($lang, $article_id = 0)
    {
        if($article_id == 0){
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
        }
        else{
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->get();
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

        // dd($outdoors, $route_quantity);
        
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

    public function news_list($lang)
    {
        $article_count = Article::where('category', '=', 'news')->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::latest('id')->where('category', '=', 'news')->where('published', '=', 1)->simplePaginate(6);
            $articles = ArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }

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
        
        if ($request->category == "outdoor") {
            $article = $this->outdoor_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "indoor") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "ice") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "news") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "other") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "tech_tip") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "partnerss") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "event") {
            $article = $this->article_page($request->lang, $request->url_title);
        }
        elseif ($request->category == "mount_route") {
            $article = $this->mount_route_page($request->lang, $request->url_title);
        }

        return $article;

    }

    public function outdoor_page($lang, $url_title)
    {
        $global_article_count = Article::where('url_title',strip_tags($url_title))->where('published', '=', 1)->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->first();
            $article = ArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article;
        }
        else{
            return abort(404);
        }
    }

    public function article_page($lang, $url_title)
    {
        $global_article_count = Article::where('url_title',strip_tags($url_title))->where('published', '=', 1)->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->where('published', '=', 1)->first();
            $article = ArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article;
        }
        else{
            return abort(404);
        }
    }

    public function mount_route_page($lang, $url_title)
    {
        $global_article_count = Article::where('url_title',strip_tags($url_title))->where('published', '=', 1)->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->first();
            $article = ArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article;
        }
        else{
            return abort(404);
        }
    }

    public function get_editing_data(Request $request)
    {
        $global_article = Article::where('id',strip_tags($request->id))->first();
        $us_article = Locale_article::where('id',strip_tags($global_article->us_article_id))->first();
        $ka_article = Locale_article::where('id',strip_tags($global_article->ka_article_id))->first();
        $ru_article = Locale_article::where('id',strip_tags($global_article->ru_article_id))->first();

        $blobal_data = [];

        if($global_article->general_info){
            foreach ($global_article->general_info as $info) {
                
                $info_position = General_info_article::
                                        where('article_id',strip_tags($info->pivot->article_id))->
                                        where('info_id',strip_tags($info->pivot->info_id))->first();

                array_push($blobal_data, [
                    'data' => $info,
                    'position' => $info_position
                ]);
            }
        }

        $data = [
            "global_article" => $global_article,
            "general_data" => $blobal_data,
            "us_article" => $us_article,
            "ka_article" => $ka_article,
            "ru_article" => $ru_article,
        ];
        
        if($global_article['category'] == 'outdoor'){
            if(isset($global_article->outdoor_region[0]->id)){
                $data['global_article']['region_id'] = $global_article->outdoor_region[0]->id;
            }
        }
        else if($global_article['category'] == 'mount_route'){
            if(isset($global_article->mount_masiv[0]->id)){
                $data['global_article']['mount_id'] = $global_article->mount_masiv[0]->id;
            }
        }
        
        return $data;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_article(Request $request)
    {
        $global_id=$request->article_id;

        $global_article = Article::where('id',strip_tags($global_id))->first();

        $image_path = $global_article->category.'_img/';

        if ($global_article->general_info->count() > 0) {
            foreach ($global_article->general_info as $del_info) {
                $deliting_info = General_info_article::
                                                    where('article_id',strip_tags($del_info->pivot->article_id))->
                                                    where('info_id',strip_tags($del_info->pivot->info_id))->
                                                    first();
                $deliting_info -> delete();
            }
        }

        ArticlesService::del_content($global_id, Article::class, Locale_article::class, '_article', $image_path);

        ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');
    }
}
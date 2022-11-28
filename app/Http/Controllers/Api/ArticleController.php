<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Services\ImageEditService;
use App\Services\ImageControllService;
use App\Services\URLTitleService;
use App\Services\GetArticlesService;

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

// use App\Models\Interested_event;
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
        // return Article::latest('id')->where('category', '=', $request->category)->get();
        $global_news = Article::where('category', '=', $request->category)->get();
        $news = GetArticlesService::get_locale_article_use_locale($global_news, $request->locale);
        return $news;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd('$request');
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
        // dd($url_title);
        $global_news = Article::where('url_title',strip_tags($url_title))->first();
        $news = GetArticlesService::get_locale_article_in_page($global_news);

        return $news[0];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd($id);
        if ($request -> isMethod('post')) {
            dd('post');
        } else {
            dd('get');
        }
        
    }
    
    public function get_article_global_data(Request $request)
    {
        dd($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
    }

    public function add_article(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $ka_validate = $this->local_article_validate($data['ka_data']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_article_validate($data['en_data']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_article_validate($data['ru_data']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_article_validate($data['global_data']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        $image_validate = $this->image_validate($request);
        if ($image_validate != null) {
            $validation_issets['image_validation'] = $image_validate;
        }
        else{
            $validation_issets['image_validation'] = false;
        }

        // if($data['global_data']["category"] == 'outdoor'){
        //         $outdoor_area_images_validate = $this->images_array_validate($request);
        //         if ($outdoor_area_images_validate != null) {
        //             $validation_issets['outdoor_area_images_validation'] = $outdoor_area_images_validate;
        //         }
        //         else{
        //             $validation_issets['outdoor_area_images_validation'] = false;
        //         }
        // }
        // else if($data['global_data']["category"] == 'mount_route'){
        //     if($request->hasFile('mountain_route_images')){

        //     }
        // }

        if (
            !$validation_issets['image_validation'] && 
            !$validation_issets['global_info_validation'] && 
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            $saiving_issets['ka_info_status'] = $this->add_locale_article($data['ka_data'], 'ka');
            $saiving_issets['ru_info_status'] = $this->add_locale_article($data['ru_data'], 'ru');
            $saiving_issets['us_info_status'] = $this->add_locale_article($data['en_data'], 'us');

            if (
                $saiving_issets['ka_info_status'] != 'Error' &&
                $saiving_issets['ru_info_status'] != 'Error' &&
                $saiving_issets['us_info_status'] != 'Error'
            ) {
                $action_article_id = $this->addGlobalArticle(
                    $data['global_data'], 
                    $global_blocks,
                    $request,

                    $saiving_issets['ka_info_status'],
                    $saiving_issets['ru_info_status'],
                    $saiving_issets['us_info_status']                    
                );

                if($data['global_data']["category"] == 'outdoor'){
                    if($request->hasFile('outdoor_area_images')){
                        $this->add_outdoor_area_images($request->outdoor_area_images, $action_article_id);
                    }
                }
                else if($data['global_data']["category"] == 'mount_route'){
                    if($request->hasFile('mount_route_images')){
                        $this->add_mount_route_images($request->outdoor_area_images, $action_article_id);
                    }
                }
                // dd($data['global_data']["category"]);
            }
        }
        else{            
            return response()->json([
                $validation_issets
                // 'Data validation' => $validation_issets
            ], 422);
        }
    }

    public function images_array_validate($images)
    {
        // 
    }

    public function add_outdoor_area_images($images, $article_id)
    {
        foreach ($images as $image) {
            $extension = $image->getClientOriginalExtension();
            $filename  = $image->getClientOriginalName();
            $pieces = explode( '.', $filename );
            $file_new_name = date('Y-m-d-H-m-s'); 
            $file_new_name = $file_new_name.'.'.$extension;

            $image -> move(public_path('images/spot_rocks_img/'), $file_new_name);

            if(file_exists(public_path('images/spot_rocks_img/') . '/' . $file_new_name)){
                return 'Upload socsesful';

                $article = new Spot_rocks_image;
        
                $article['image'] = $file_new_name;
                $article['article_id'] = $article_id;
        
                $article -> save();
            }
            else{
                return 'Upload error';
            }
        }
    }

    public function add_mount_route_images($images, $article_id)
    {
        foreach ($images as $image) {
            $extension = $image->getClientOriginalExtension();
            $filename  = $image->getClientOriginalName();
            $pieces = explode( '.', $filename );
            $file_new_name = date('Y-m-d-H-m-s'); 
            $file_new_name = $file_new_name.'.'.$extension;

            $image -> move(public_path('images/mount_route_img/'), $file_new_name);

            if(file_exists(public_path('images/mount_route_img/') . '/' . $file_new_name)){
                return 'Upload socsesful';

                $article = new Mount_route_image;
        
                $article['image'] = $file_new_name;
                $article['article_id'] = $article_id;
        
                $article -> save();
            }
            else{
                return 'Upload error';
            }
        }
    }

    public function addGlobalArticle($global_data, $global_blocks, $request, $ka_info_id, $ru_info_id, $us_info_id)
    {
        $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]); // make url_title from us_title value

        $article = new Article;
        if($request->hasFile('image')){
            $article['image'] =  ImageControllService::image_upload('images/'.$global_data["category"].'_img/', $request, 'image', 1);
        }
        
        $article['url_title'] = $url_title;

        $article['category']=$global_data["category"];
        $article['published']=$global_data["published"];
        $article['map']=$global_data["map"];
        $article['weather']=$global_data["weather"];

        $article['open_time'] = $global_data["open_timen"];
        $article['closed_time'] = $global_data["closed_time"];

        $article['price_from'] = $global_data["price_from"];

        $article['fb_link']=$global_data["fb_link"];
        $article['twit_link']=$global_data["twit_link"];
        $article['google_link']=$global_data["google_link"];
        $article['inst_link']=$global_data["inst_link"];
        $article['web_link']=$global_data["web_link"];

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $article['us_article_id']=$local_us;
        $article['ka_article_id']=$local_ka;
        $article['ru_article_id']=$local_ru;
        
        $article -> save();
        
        $this->add_general_info_articles($global_blocks);

        if($global_data["category"] == 'outdoor' && $global_data["region"] != 'select_region'){
            $this->add_regioin_articles($global_data["region"], $article->id);
        }
        else if($global_data["category"] == 'mount_route' && $global_data["mount"] != 'select_mount'){
            $this->add_mount_articles($global_data["mount"], $article->id);
        }

        return $article->id;
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

    public function add_general_info_articles($global_blocks)
    {
        // dd(Article::latest('id')->first());
        if($global_blocks['info_block'] != 'new_info'){
            $general_info_article = new General_info_article;

            $general_info_article['info_id']=$global_blocks["info_block_id"];
            $general_info_article['article_id']=Article::latest('id')->first()->id;
            $general_info_article['block']='info_block';
            $general_info_article['block_action']='info_block';
            $general_info_article['block_action']=$global_blocks["info_block"];

            $general_info_article -> save();
        }
        if($global_blocks['routes_info'] != 'new_info'){
            $general_info_article = new General_info_article;

            $general_info_article['info_id']=$global_blocks["routes_info_id"];
            $general_info_article['article_id']=Article::latest('id')->first()->id;
            $general_info_article['block']='routes_info';
            $general_info_article['block_action']='routes_info';
            $general_info_article['block_action']=$global_blocks["routes_info"];

            $general_info_article -> save();
        }
        if($global_blocks['what_need_info'] != 'new_info'){
            $general_info_article = new General_info_article;

            $general_info_article['info_id']=$global_blocks["what_need_info_id"];
            $general_info_article['article_id']=Article::latest('id')->first()->id;
            $general_info_article['block']='what_need_info';
            $general_info_article['block_action']=$global_blocks["what_need_info"];

            $general_info_article -> save();
        }
        if($global_blocks['best_time'] != 'new_info'){
            $general_info_article = new General_info_article;

            $general_info_article['info_id']=$global_blocks["best_time_id"];
            $general_info_article['article_id']=Article::latest('id')->first()->id;
            $general_info_article['block']='best_time';
            $general_info_article['block_action']=$global_blocks["best_time"];

            $general_info_article -> save();
        }
    }

    public function add_locale_article($data, $locale)
    {
        $article = new Locale_article;
        
        $article['title']=$data["title"];
        $article['locale']=$locale;
        $article['short_description']=$data["short_description"];
        $article['text']=$data["text"];
        $article['route']=$data["route"];
        $article['how_get']=$data["how_get"];
        $article['best_time']=$data["best_time"];
        $article['what_need']=$data["what_need"];
        $article['info']=$data["info"];

        $saved = $article->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $article->id;
        }
    }

    


    public function global_article_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'us_title_for_url_title' => 'required|unique:articles,url_title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function image_validate($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_article_validate($data)
    {
        // dd($data);
        $validator = Validator::make($data, [
            'title' => 'required',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }





    public function get_similar_locale_article(Request $request)
    {
        // dd($request->article_id, $request->article_category);
        $articles = [];
        
        if ($request->article_category == "outdoor") {
            $articles = $this->similar_outdoor_list($request->lang, $request->article_id);
        }
        elseif ($request->article_category == "indoor") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "ice") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "news") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "other") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "tech_tip" || $request->article_category == 'security') {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "partner") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "event") {
            $articles = $this->similar_article_list($request->category, $request->lang);
        }
        elseif ($request->article_category == "mount_route") {
            $articles = $this->similar_mount_route_list($request->lang);
        }

        return $articles;
    }


    public function similar_mount_route_list($lang)
    {
        $article_count = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->get();
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }


    public function similar_outdoor_list($lang, $article_id = 0)
    {
        if($article_id == 0){
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('published', '=', 1)->count();
        }
        else{
            $global_outdoors = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'outdoor')->where('id', '!=', $article_id)->where('published', '=', 1)->count();
        }

        $outdoors = GetArticlesService::get_locale_article_use_locale($global_outdoors, $lang);

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
                if ($quantity['article_id'] == $outdoor['id']) {
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
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "other") {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "tech_tip" || $request->article_category == 'security') {
            $articles = $this->article_list($request->category, $request->lang);
        }
        elseif ($request->category == "partner") {
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

        $outdoors = GetArticlesService::get_locale_article_use_locale($global_outdoors, $lang);

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
                if ($quantity['article_id'] == $outdoor['id']) {
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
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        // dd($articles);
        return $articles;

    }

    // public function mount_route_list($lang)
    // {
    //     $article_count = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->count();
    //     $articles = [];
    //     if($article_count > 0){
    //         $global_articles = Article::latest('id')->where('category', '=', 'mount_route')->where('published', '=', 1)->get();
    //         $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
    //     }
    //     return $articles;
    // }

    // public function get_last_news($lang)
    // {
    //     $article_count = Article::where('category', '=', 'news')->where('published', '=', 1) -> latest()->count();
    //     $articles = [];
    //     if($article_count > 0){
    //         $global_articles = Article::latest('id')->where('category', '=', 'news')->where('published', '=',1) -> latest()->get();
    //         $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
    //     }
    //     return [$articles];
    // }

    


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
        elseif ($request->category == "partner") {
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
        $global_article = Article::where('url_title',strip_tags($url_title))->first();
        $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

        return $article[0];
    }

    public function article_page($lang, $url_title)
    {
        $global_article = Article::where('url_title',strip_tags($url_title))->first();
        $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

        return $article[0];
    }

    public function mount_route_page($lang, $url_title)
    {
        $global_article = Article::where('url_title',strip_tags($url_title))->first();
        $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

        return $article[0];
    }

    public function get_editing_data(Request $request)
    {
        $global_article = Article::where('id',strip_tags($request->id))->first();
        $us_article = Locale_article::where('id',strip_tags($global_article->us_article_id))->first();
        $ka_article = Locale_article::where('id',strip_tags($global_article->ka_article_id))->first();
        $ru_article = Locale_article::where('id',strip_tags($global_article->ru_article_id))->first();

        return(
            $data = [
                "global_article" => $global_article,
                "general_data" => $general_data = $global_article->general_info,
                "us_article" => $us_article,
                "ka_article" => $ka_article,
                "ru_article" => $ru_article,
            ]
        );
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $global_id=$id;

        $global_article = Article::where('id',strip_tags($global_id))->first();
        $us_article = Locale_article::where('id',strip_tags($global_article->us_article_id))->first();
        $ru_article = Locale_article::where('id',strip_tags($global_article->ru_article_id))->first();
        $ka_article = Locale_article::where('id',strip_tags($global_article->ka_article_id))->first();
        
        // delete article file
        ImageControllService::image_delete('images/'.$global_article->category.'_img/', $global_article, 'image');
        ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');

        // delete article from db
        $global_article ->delete();
        $us_article ->delete();
        $ru_article ->delete();
        $ka_article ->delete();
    }
}

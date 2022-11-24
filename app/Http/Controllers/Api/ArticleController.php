<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Locale_article;
use App\Services\GetArticlesService;
use App\Models\Comment;

use App\Models\Spot_rocks_image;

use App\Services\ImageEditService;
use App\Services\ImageControllService;

use App\Services\URLTitleService;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

use App\Models\Mount;
use App\Models\Locale_mount;

use App\Models\General_info_article;
use App\Models\General_info;

use App\Models\Region;
use App\Models\Region_article;

use App\Models\Interested_event;
use App\Models\Favorite_outdoor_area;

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

    public function upload_spot_rock_images(Request $request)
    {   
        // dd($request->image);
        // https://therichpost.com/vue-laravel-image-upload/ 
        // if ($request->hasFile('profile_pic')){ 

            $spot_sectors_rock = new Spot_rocks_image;
            $file_new_name = ImageControllService::image_upload('images/spot_rocks_img/', $request, 'image');

            $spot_sectors_rock['image'] = $file_new_name;
            $spot_sectors_rock -> save();
        // }
    }

    public function image_upload(Request $request)
    {   
        // https://therichpost.com/vue-laravel-image-upload/ 
        // if ($request->hasFile('profile_pic')){ 
            // $last_global_article_id = 0;
            // $last_global_article_category = '';

            $last_article = Article::latest('id')->first();
            // foreach ($global_article as $global) {
            //     $last_global_article_id = $global->id;
            //     $last_global_article_category = $global->category;
            // }
            // $article = Article::where('id',strip_tags($last_global_article_id))->first();

            // dd($request->image);
            
            $file_new_name = ImageControllService::image_upload('images/article_img/', $request, 'image', 1);

            $last_article['image'] = $file_new_name;
            $last_article -> save();
            // return response()->json(["message" => "Image Uploaded Succesfully"]);
        // }
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
        $ka_validate = $this->add_locale_article($request->data['ka_data'], 'ka');
        $ru_validate = $this->add_locale_article($request->data['ru_data'], 'ru');
        $us_validate = $this->add_locale_article($request->data['en_data'], 'us');

        $globa_validate = $this->addGlobalArticle(
            $request->data['global_data'], 
            $request->global_blocks,
        );

        if ($globa_validate || $ka_validate || $ru_validate || $us_validate) {
            $errors = [ 'global_data' => $globa_validate, 
                        'ka_data' => $ka_validate, 
                        'ru_data' => $ru_validate, 
                        'us_data' => $us_validate
                    ];
            return response()->json($errors, 422);
            // return $errors;
        }
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


    


    public function addGlobalArticle($global_data, $global_blocks)
    {
        // if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            // $validate = $this->global_article_validate($global_data);
            // if ($validate != null) {
            //     return $validate;
            // }

            // $global_article = Article::get();
            // foreach ($global_article as $global) {
            //     $last_globale_id = $global->id;
            // }


            // dd(Locale_article::latest('id')->where('locale', '=', 'us')->get()[0]->id);

            $validate = $this->global_article_validate($global_data);

            if ($validate != null) {
                return($validate);
            }
            else{
                // make url_title from us_title value
                $url_title = URLTitleService::get_url_title($global_data["us_title_for_url_title"]);

                $article = new Article;
                
                $article['url_title'] = $url_title;

                $article['category']=$global_data["category"];
                $article['published']=$global_data["published"];
                // $article['completed']=$global_data["completed"];
                $article['map']=$global_data["map"];
                $article['weather']=$global_data["weather"];

                // $article['start_data']=$global_data["start_data"];
                // $article['end_data']=$global_data["end_data"];

                $article['open_time'] = $global_data["open_timen"];
                $article['closed_time'] = $global_data["closed_time"];

                $article['price_from'] = $global_data["price_from"];

                $article['fb_link']=$global_data["fb_link"];
                $article['twit_link']=$global_data["twit_link"];
                $article['google_link']=$global_data["google_link"];
                $article['inst_link']=$global_data["inst_link"];
                $article['web_link']=$global_data["web_link"];

                $local_us = Locale_article::latest('id')->where('locale', '=', 'us')->get()[0]->id;
                $local_ka = Locale_article::latest('id')->where('locale', '=', 'ka')->get()[0]->id;
                $local_ru = Locale_article::latest('id')->where('locale', '=', 'ru')->get()[0]->id;


                $article['us_article_id']=$local_us;
                $article['ka_article_id']=$local_ka;
                $article['ru_article_id']=$local_ru;
                
                $article -> save();

                // if ($article->save())
                // {
                    // Article::letest('id')->first();
                    $this->add_general_info_articles($global_blocks);
                // }

            }
        // }
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
        // $article = Locale_article::find($last_article_id);
        $validate = $this->local_article_validate($data);

        if ($validate != null) {
            return($validate);
        }
        else{
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

            // $article -> update();

            $article->save();
        }
    }

    public function add_to_interested_events(Request $request)
    {
        if (Auth::user()) {
            
            if(Interested_event::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->event_id)->count() > 0){
                return 'this event are in faworite';
            }
            else{
                $faworit = new Interested_event();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['article_id'] = $request->event_id;
                
                $faworit -> save();

                return 'event eded socsesful';
            }
        }
        else{
            return 'ples login';
        }
    }

    public function get_interested_events(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->get();
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

    public function del_interested_event(Request $request)
    {
        if (Auth::user()) {
            $fav_area = Interested_event::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->first();
            $fav_area ->delete();
        }
        else{
            return 'Plees login!';
        }
    }

    public function add_to_favorite_outdoor_area(Request $request)
    {
        if (Auth::user()) {
            
            if(Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->count() > 0){
                // $editing_faworit = Favorite_outdoor_area::where('user_id', '=', Auth::user()->id)->where('article_id', '=', $request->article_id)->first();

                // $editing_faworit['user_id'] = Auth::user()->id;
                // $editing_faworit['article_id'] = $request->article_id;
                
                // $editing_faworit -> save();

                return 'this area are in faworite';
            }
            else{
                $faworit = new Favorite_outdoor_area();
            
                $faworit['user_id'] = Auth::user()->id;
                $faworit['article_id'] = $request->article_id;
                
                $faworit -> save();

                return 'area eded socsesful';
            }
        }
        else{
            return 'ples login';
        }
    }

    public function global_article_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function local_article_validate($data)
    {
        $validator = Validator::make($data, [
            'title' => 'required',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        // dd($validator->messages());
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

    // public function similar_article_list($category, $lang)
    // {
    //     // dd($category, $lang);
    //     $article_count = Article::where('category', '=', $category)->where('published', '=', 1)->count();
    //     $articles = [];
    //     if($article_count > 0){
    //         $global_articles = Article::where('category', '=', $category)->where('published', '=', 1)->get();
    //         $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
    //     }
    //     // dd($articles);
    //     return $articles;

    // }

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
            $articles = $this->mount_route_list($request->lang);
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
            $global_articles = Article::where('category', '=', $category)->where('published', '=', 1)->get();
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        // dd($articles);
        return $articles;

    }

    public function mount_route_list($lang)
    {
        $article_count = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', 'mount_route')->where('published', '=', 1)->get();
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }

    public function get_last_news($lang)
    {
        $article_count = Article::where('category', '=', 'news')->where('published', '=', 1) -> latest()->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', 'news')->where('published', '=',1) -> latest()->get();
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return [$articles];
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
}

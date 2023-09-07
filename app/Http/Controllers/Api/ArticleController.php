<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


// use App\Services\ImageEditService;
use App\Services\ImageControllService;
use App\Services\URLTitleService;
use App\Services\GetArticlesService;
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
    // public function edit($id)
    // {
    //     dd($id);
    //     if ($request -> isMethod('post')) {
    //         dd('post');
    //     } else {
    //         dd('get');
    //     }
        
    // }
    
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
    public function edit_article(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $ka_validate = $this->local_article_validate($data['ka_article']);
        if ($ka_validate != null) {
            $validation_issets['ka_info_validation'] = $ka_validate;
        }
        else{
            $validation_issets['ka_info_validation'] = false;
        }

        $us_validate = $this->local_article_validate($data['us_article']);
        if ($us_validate != null) {
            $validation_issets['us_info_validation'] = $us_validate;
        }
        else{
            $validation_issets['us_info_validation'] = false;
        }

        $ru_validate = $this->local_article_validate($data['ru_article']);
        if ($ru_validate != null) {
            $validation_issets['ru_info_validation'] = $ru_validate;
        }
        else{
            $validation_issets['ru_info_validation'] = false;
        }

        $global_validate = $this->global_article_editing_validate($data['global_article']);
        if ($global_validate != null) {
            $validation_issets['global_info_validation'] = $global_validate;
        }
        else{
            $validation_issets['global_info_validation'] = false;
        }

        if (
            !$validation_issets['ru_info_validation'] && 
            !$validation_issets['ka_info_validation'] && 
            !$validation_issets['us_info_validation']
        ) {
            if (!$validation_issets['global_info_validation']){
                $is_change = false;
                $changed_title = '';
                if (in_array("change_url_title", $data['us_article'])) {
                    $is_change = true;
                    if ($data['us_article']['title']) {
                        $changed_title = $data['us_article']['title'];
                    }
                }
                $action_article_id = $this->editGlobalArticle(
                    $data['global_article'], 
                    $global_blocks,
                    $request, 
                    $is_change,
                    $changed_title
                    
                );
            }

            if ($action_article_id != 'Error') {
                $this->edit_locale_article($data['ka_article'], $action_article_id['ka_article']);
                $this->edit_locale_article($data['ru_article'], $action_article_id['ru_article']);
                $this->edit_locale_article($data['us_article'], $action_article_id['us_article']);

                if($data['global_article']["category"] == 'outdoor'){
                    if($request->hasFile('outdoor_area_images')){
                        $this->add_outdoor_area_images($request->outdoor_area_images, $request->article_id);
                    }
                }
                else if($data['global_article']["category"] == 'mount_route'){
                    // dd($request->hasFile('mount_route_images'));
                    if($request->hasFile('mount_route_images')){
                        $this->add_mount_route_images($request->mount_route_images, $request->article_id);
                    }
                }
            }
        }
        else{            
            return response()->json([
                'validation' => $validation_issets
            ], 422);
        }
    }

    public function editGlobalArticle($global_data, $global_blocks, $request, $is_title_change, $title)
    {
        $editing_article = Article::where('id', '=', $request->article_id)->first();

        if($request->hasFile('image')){
            $editing_article['image'] =  ImageControllService::image_upload('images/'.$global_data["category"].'_img/', $request, 'image', 1);
        }
        
        if($is_title_change){
            $url_title = URLTitleService::get_url_title($title); // make url_title from us_title value
            $editing_article['url_title'] = $url_title;
        }

        // $editing_article['category']=$global_data["category"];
        $editing_article['published']=$global_data["published"];
        $editing_article['map']=$global_data["map"];
        $editing_article['weather']=$global_data["weather"];

        $editing_article['open_time'] = $global_data["open_time"];
        $editing_article['closed_time'] = $global_data["closed_time"];

        $editing_article['price_from'] = $global_data["price_from"];
        
        $saved = $editing_article -> save();
        
        GeneralInfoService::edit_general_info_relatione($global_blocks, $request->article_id, 'article');

        if($global_data["category"] == 'outdoor'){
            if(isset($global_data['region_id'])){
                $article_region = Article_region::where('article_id', '=', $request->article_id)->first();
                if($article_region){
                    if($article_region['region_id'] != $global_data['region_id']){
                        $article_region['article_id'] = $request->article_id;
                        $article_region['region_id'] = $global_data['region_id'];
                        $article_region -> save();
                    }
                }
                else{
                    $new_mount = new Article_region;
                    $new_mount['article_id'] = $request->article_id;
                    $new_mount['region_id'] = $global_data['region_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($global_data['region_id'])){
                $deliting_article_region = Article_region::where('article_id', '=', $request->article_id)->first();
                if($deliting_article_region){
                    $deliting_article_region -> delete();
                }
            }
        }
        
        if($global_data["category"] == 'mount_route'){
            if(isset($global_data['mount_id'])){
                $article_mount = Article_mount::where('article_id', '=', $request->article_id)->first();
                if($article_mount){
                    if($article_mount['mount_id'] != $global_data['mount_id']){
                        $article_mount['article_id'] = $request->article_id;
                        $article_mount['mount_id'] = $global_data['mount_id'];
                        $article_mount -> save();
                    }
                }
                else{
                    $new_mount = new Article_mount;
                    $new_mount['article_id'] = $request->article_id;
                    $new_mount['mount_id'] = $global_data['mount_id'];
                    $new_mount -> save();
                }
            }
            else if(!isset($global_data['mount_id'])){
                $deliting_article_mount = Article_mount::where('article_id', '=', $request->article_id)->first();
                if($deliting_article_mount){
                    $deliting_article_mount -> delete();
                }
            }
        }

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            $locale_articles = [
                'us_article' => $editing_article['us_article_id'],
                'ka_article' => $editing_article['ka_article_id'],
                'ru_article' => $editing_article['ru_article_id'],
            ];
    
            return $locale_articles;
        }
    }

    public function edit_locale_article($data, $locale_article_id)
    {
        $editing_local_article = Locale_article::where('id', '=', $locale_article_id)->first();
        
        $editing_local_article['title']=$data["title"];
        $editing_local_article['short_description']=$data["short_description"];
        $editing_local_article['text']=$data["text"];
        $editing_local_article['route']=$data["route"];
        $editing_local_article['how_get']=$data["how_get"];
        $editing_local_article['best_time']=$data["best_time"];
        $editing_local_article['what_need']=$data["what_need"];
        $editing_local_article['info']=$data["info"];

        $saved = $editing_local_article->save();

        if(!$saved){
            App::abort(500, 'Error');
        }
        else{
            return $editing_local_article->id;
        }
    }


    public function add_article(Request $request)
    {
        $validation_issets = [];

        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        // dd($request->global_blocks);

        // dd($data['global_data']);
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

        // dd($data['global_data']);
        $global_validate = $this->global_article_adding_validate($data['global_data']);
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
                    // dd($request->hasFile('mount_route_images'));
                    if($request->hasFile('mount_route_images')){
                        $this->add_mount_route_images($request->mount_route_images, $action_article_id);
                    }
                }
                // dd($data['global_data']["category"]);
            }
        }
        else{            
            return response()->json([
                // $validation_issets
                'validation' => $validation_issets
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
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
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

        $local_us = $us_info_id;
        $local_ka = $ka_info_id;
        $local_ru = $ru_info_id;

        $article['us_article_id']=$local_us;
        $article['ka_article_id']=$local_ka;
        $article['ru_article_id']=$local_ru;
        
        $article -> save();

        if($global_data['region_id'] != "select_region"){
            if($global_data["category"] == 'outdoor'){
                $new_region = new Article_region;
                $new_region['article_id'] = $article->id;
                $new_region['region_id'] = $global_data['region_id'];
                $new_region -> save();
            }
        }
        if($global_data['mount_id'] != "select_mount"){
            if($global_data["category"] == 'mount_route'){
                $new_mount = new Article_mount;
                $new_mount['article_id'] = $article->id;
                $new_mount['mount_id'] = $global_data['mount_id'];
                $new_mount -> save();
            }
        }
        
        // $this->add_general_info_articles($global_blocks);
        // dd($global_blocks);

        GeneralInfoService::add_general_info_relatione($global_blocks, $article->id, 'article');

        if($global_data["category"] == 'outdoor' && $global_data["region_id"] != 'select_region'){
            $this->add_regioin_articles($global_data["region_id"], $article->id);
        }
        else if($global_data["category"] == 'mount_route' && $global_data["mount_id"] != 'select_mount'){
            $this->add_mount_articles($global_data["mount_id"], $article->id);
        }

        return $article->id;
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

    public function global_article_editing_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            // 'us_title_for_url_title' => 'required|unique:articles,url_title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    public function global_article_adding_validate($global_data)
    {
        $validator = Validator::make($global_data, [
            'published' => 'required',
            'us_title_for_url_title' => 'required|unique:locale_articles,title',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private function image_validate($request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable | image | mimes:jpeg,png,jpg,gif,svg | max:2048',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }

    private function local_article_validate($data)
    {
        // dd($data);
        $validator = Validator::make($data, [
            'title' => 'required | max:190',
            'short_description' => 'required',
            'text' => 'required',
        ]);
        if ($validator->fails()) {
            // dd($validator->messages());
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
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
        }
        return $articles;
    }


    public function similar_mount_route_list($lang, $article_id)
    {
        $article_count = Article::where('category', '=', 'mount_route')->where('id', '!=', $article_id)->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::where('category', '=', 'mount_route')->where('id', '!=', $article_id)->where('published', '=', 1)->simplePaginate(4);
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
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

    public function news_list($lang)
    {
        $article_count = Article::where('category', '=', 'news')->where('published', '=', 1)->count();
        $articles = [];
        if($article_count > 0){
            $global_articles = Article::latest('id')->where('category', '=', 'news')->where('published', '=', 1)->simplePaginate(6);
            $articles = GetArticlesService::get_locale_article_use_locale($global_articles, $lang);
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
        $global_article_count = Article::where('url_title',strip_tags($url_title))->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->first();
            $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article[0];
        }
        else{
            return abort(404);
        }
    }

    public function article_page($lang, $url_title)
    {
        $global_article_count = Article::where('url_title',strip_tags($url_title))->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->first();
            $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article[0];
        }
        else{
            return abort(404);
        }
    }

    public function mount_route_page($lang, $url_title)
    {
        $global_article_count = Article::where('url_title',strip_tags($url_title))->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($url_title))->first();
            $article = GetArticlesService::get_locale_article_in_page($global_article, $lang);

            return $article[0];
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
        $us_article = Locale_article::where('id',strip_tags($global_article->us_article_id))->first();
        $ru_article = Locale_article::where('id',strip_tags($global_article->ru_article_id))->first();
        $ka_article = Locale_article::where('id',strip_tags($global_article->ka_article_id))->first();
        
        // delete article file
        ImageControllService::image_delete('images/'.$global_article->category.'_img/', $global_article, 'image');
        ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');
        
        // Del general info article relatione
        if ($global_article->general_info->count() > 0) {
            foreach ($global_article->general_info as $del_info) {
                $deliting_info = General_info_article::
                                                    where('article_id',strip_tags($del_info->pivot->article_id))->
                                                    where('info_id',strip_tags($del_info->pivot->info_id))->
                                                    first();
                $deliting_info -> delete();
            }
        }

        // delete article from db
        $global_article ->delete();
        $us_article ->delete();
        $ru_article ->delete();
        $ka_article ->delete();
    }
}
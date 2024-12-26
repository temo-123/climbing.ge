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
        return Article::latest('id')->
                        where('category', '!=', 'news')->
                        where('category', '!=', 'partner')->
            get();
    }

    public function add_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );
        
        $image_path = 'images/'.$data['global_article']['category'].'_img/';

        $article_adding = ArticlesService::add_content($data, Article::class, Locale_article::class, '_article', $request, $image_path);
        
        if (!array_key_exists('validation', $article_adding->original)) {
            GalleryService::add_gallery_images(
                $request->gallery_images, 
                $article_adding->original['global_article_id'], 
                Article_image::class, 
                'image', 
                'article_id', 
                '/images/article_gallery_img/'
            );

            GeneralInfoService::add_general_info_relatione($global_blocks, $article_adding->original['global_article_id'], 'article');

            $this->description_img($article_adding->original['global_article_id'], $global_blocks, $data, $request);
        }
        else {
            return $article_adding;
        }
        
    }


    public function edit_article(Request $request)
    {
        $data = json_decode($request->data, true );
        $global_blocks = json_decode($request->global_blocks, true );

        $image_path = 'images/'.$data['global_article']['category'].'_img/';

        $article_editing = ArticlesService::edit_content(Article::class, Locale_article::class, '_article', $request, $image_path);

        if(!array_key_exists('validation', $article_editing->original)){
            GalleryService::add_gallery_images(
                $request->gallery_images, 
                $article_editing->original['global_article_id'], 
                Article_image::class, 
                'image', 
                'article_id', 
                '/images/article_gallery_img/'
            );

            GeneralInfoService::edit_general_info_relatione($global_blocks, $article_editing->original['global_article_id'], 'article');

            $this->description_img($article_editing->original['global_article_id'], $global_blocks, $data, $request);
        }
        else{
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

    public function get_article_for_bisnes_page(Request $request)
    {
        $bisnes = Suport_local_bisnes::where('url_title', '=', $request->bisnes_url_title)->first();

        $article = $bisnes->bisnes_article->first();

        if($article->count() > 0){
            $global_articles = Article::where('id', '=', $article->id)->where('published', '=', 1)->get();
            return ArticlesService::get_locale_article_use_locale($global_articles, $request->lang);
        }
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

        $global_article_count = Article::where('url_title',strip_tags($request->url_title))->where('published', '=', 1)->count();
        if ($global_article_count > 0) {
            $global_article = Article::where('url_title',strip_tags($request->url_title))->where('published', '=', 1)->first();
            $article = ArticlesService::get_locale_article_in_page($global_article, $request->lang);

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
        // $ru_article = Locale_article::where('id',strip_tags($global_article->ru_article_id))->first();

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
            "gallery_images" => $global_article->gallery_images,
            "general_data" => $blobal_data,
            "us_article" => $us_article,
            "ka_article" => $ka_article,
            // "ru_article" => $ru_article,
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

        $image_path = 'images/'.$global_article->category.'_img/';

        if ($global_article->general_info->count() > 0) {
            foreach ($global_article->general_info as $del_info) {
                $deliting_info = General_info_article::
                                                    where('article_id',strip_tags($del_info->pivot->article_id))->
                                                    where('info_id',strip_tags($del_info->pivot->info_id))->
                                                    first();
                $deliting_info -> delete();
            }
        }

        if ($global_article->gallery_images->count() > 0) {
            foreach ($global_article->gallery_images as $del_img) {
                ImageControllService::image_delete('images/article_gallery_img/', $del_img, 'image');
                $del_img -> delete();
            }
        }

        ArticlesService::del_content($global_id, Article::class, Locale_article::class, '_article', 'image', $image_path);

        ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');
    }
}
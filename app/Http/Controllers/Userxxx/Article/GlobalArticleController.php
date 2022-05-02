<?php

namespace App\Http\Controllers\User\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;
use App\Services\URLTitleService;
use App\Services\ImageControllService;
// use Intervention\Image\ImageManagerStatic as Image;
// use Intervention\Image\Facades\Image;

class GlobalArticleController extends Controller
{
    public function add_global_article(Request $request)
	{
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->global_article_validate($request);

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_globale_id = $global->id;
            }

            // $us_articl = Us_article::get();
            // foreach ($us_articl as $us) {
            //     $last_us_article_id = $us->id;
            // }

            // $ka_articl = Ka_article::get();
            // foreach ($ka_articl as $ka) {
            //     $last_ka_article_id = $ka->id;
            // }

            // $ru_articl = Ru_article::get();
            // foreach ($ru_articl as $ru) {
            //     $last_ru_article_id = $ru->id;
            // }

            // make url_title from us_title value
            $url_title = URLTitleService::get_url_title($request->us_title_for_url_title);

            $article = Article::find($last_globale_id);
            
            $article['url_title'] = $url_title;

            $article['category']=$request->category;
            $article['mount_id']=$request->mount_id;
            $article['published']=$request->published;
            $article['completed']=$request->completed; 
            $article['map']=$request->map;
            $article['weather']=$request->weather; 

            $article['start_data_day']=$request->start_data_day;
            $article['end_data_day']=$request->end_data_day;
            $article['end_data_month']=$request->end_data_month;
            $article['start_data_month']=$request->start_data_month;

            $article['working_time'] = $request->working_time;
            $article['price_from'] = $request->price_from;

            $article['fb_link']=$request->fb_link;
            $article['twit_link']=$request->twit_link; 
            $article['google_link']=$request->google_link;
            $article['inst_link']=$request->inst_link;
            $article['web_link']=$request->web_link;

            // $article['us_article_id']=$last_us_article_id;
            // $article['ru_article_id']=$last_ru_article_id;
            // $article['ka_article_id']=$last_ka_article_id;
            
            $article -> update();

            // $last_ru_article_id = 0;
            // $last_us_article_id = 0;
            // $last_ka_article_id = 0;
        }
        
        
    }



    public function edit_global_article(article $article, Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            
            $this->global_article_validate($request);

            // make url_title from us_title value
            $us_title_arr = explode( ' ', $request->us_title_for_url_title);
            $url_title = implode("_", $us_title_arr);
            
            $global_article = Article::find($request->id);

            $global_article->url_title = $url_title;

            $global_article->published = $request->published;
            $global_article->mount_id = $request->mount_id;
            $global_article->completed = $request->completed;
            $global_article->map = $request->map; 
            $global_article->weather = $request->weather;

            $global_article->start_data_day = $request->start_data_day; 
            $global_article->end_data_day = $request->end_data_day;
            $global_article->start_data_month = $request->start_data_month;
            $global_article->end_data_month = $request->end_data_month;

            $global_article->working_time = $request->working_time;
            $global_article->price_from = $request->price_from;

            $global_article->fb_link = $request->fb_link;
            $global_article->twit_link = $request->twit_link;
            $global_article->google_link = $request->google_link;
            $global_article->inst_link = $request->inst_link;
            $global_article->web_link = $request->web_link;
            
            $global_article->update();
        }

    }
    


    public function image_upload(Request $request)
    {   
        // https://therichpost.com/vue-laravel-image-upload/ 
        if ($request->hasFile('profile_pic')){ 
            $last_global_article_id = 0;
            $last_global_article_category = '';

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_global_article_id = $global->id;
                $last_global_article_category = $global->category;
            }
            $article = Article::where('id',strip_tags($last_global_article_id))->first();
            
            $file_new_name = ImageControllService::image_upload('images/'.$last_global_article_category.'_img/', $request, 'profile_pic', 1);

            $article['image'] = $file_new_name;
            $article -> save();
            // return response()->json(["message" => "Image Uploaded Succesfully"]);
        }
    }

    public function image_update(Request $request)
    {
        if ($request->hasFile('profile_pic')){
            $article = Article::where('id',strip_tags($request->id))->first();
            $file_new_name = ImageControllService::image_update('images/'.$article->category.'_img/', $article, $request, 'profile_pic', 1, 'image');

            $article['image'] = $file_new_name;
            $article -> save();
        }
    }

    public function region_sectors_image_upload(Request $request)
    { 
        if ($request->hasFile('region_sectors_img')){ 

            $last_global_article_id = 0;
            $last_global_article_category = '';

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_global_article_id = $global->id;
                $last_global_article_category = $global->category;
            }
            $article = Article::where('id',strip_tags($last_global_article_id))->first();
            
            $file_new_name = ImageControllService::image_upload('images/region_sectors_img/', $request, 'region_sectors_img', 1);

            $article['climbing_area_image'] = $file_new_name;
            $article -> save();
        }
    }

    public function mount_route_image_upload(Request $request)
    { 
        if ($request->hasFile('mount_route_img')){ 

            $last_global_article_id = 0;
            $last_global_article_category = '';

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_global_article_id = $global->id;
                $last_global_article_category = $global->category;
            }
            $article = Article::where('id',strip_tags($last_global_article_id))->first();
            
            $file_new_name = ImageControllService::image_upload('images/mount_route_description_img/', $request, 'mount_route_img', 1);

            $article['climbing_area_image'] = $file_new_name;
            $article -> save();
        }
    }

    public function region_sectors_image_update(Request $request)
    {
        if ($request->hasFile('region_sectors_img')){
            $article = Article::where('id',strip_tags($request->id))->first();
            $file_new_name = ImageControllService::image_update('images/region_sectors_img/', $article, $request, 'region_sectors_img', 1, 'climbing_area_image');

            $article['climbing_area_image'] = $file_new_name;
            $article -> save();
        }
    }

    public function mount_route_image_update(Request $request)
    {
        // dd('test');
        if ($request->hasFile('mount_route_img')){
            // dd('test2');
            $article = Article::where('id',strip_tags($request->id))->first();
            $file_new_name = ImageControllService::image_update('images/mount_route_description_img/', $article, $request, 'mount_route_img', 1, 'mount_route_image');

            $article['mount_route_image'] = $file_new_name;
            $article -> save();
        }
    }

    
}

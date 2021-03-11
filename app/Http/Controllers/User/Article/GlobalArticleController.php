<?php

namespace App\Http\Controllers\User\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;
use App\Services\URLTitleService;
use Intervention\Image\ImageManagerStatic as Image;
// use Intervention\Image\Facades\Image;

class GlobalArticleController extends Controller
{
    public function add_global_article(Request $request)
	{
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->global_sector_validate($request);

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
            $article['and_data_day']=$request->and_data_day;
            $article['and_data_month']=$request->and_data_month;
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
            
            $this->global_sector_validate($request);

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
            $global_article->and_data_day = $request->and_data_day;
            $global_article->start_data_month = $request->start_data_month;
            $global_article->and_data_month = $request->and_data_month;

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
            // $this->global_sector_image_validate($request);

            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_global_article_id = $global->id;
                $last_global_article_category = $global->category;
            }

            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;

            // push image in folder
            $file->move(public_path('images/'.$last_global_article_category.'_img'), $file_new_name);

            $article = Article::where('id',strip_tags($last_global_article_id))->first();
            $article['image']=$file_new_name;
            $article -> save();



            // open an image file
            $img = Image::make('images/'.$last_global_article_category.'_img'.$file_new_name);

            // now you are able to resize the instance
            $img->resize(320, 240);

            // and insert a watermark for example
            // $img->insert('images/'.$last_global_article_category.'_img'.$file_new_name);

            // finally we save the image as a new file
            $img->move(public_path('images/'.$last_global_article_category.'_img/demo_img/'), $file_new_name);



            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } 
        else{
            return response()->json(["message" => "Select image first."]);
        }
    }

    public function image_update(Request $request)
    {
        if ($request->hasFile('profile_pic')){ 
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;

            // fined article category
            $global_article = Article::where('id',strip_tags($request->id))->first();;
            $article_category = $global_article->category;

            // push image in folder
            $file->move(public_path('images/'.$article_category.'_img/origin_img'), $file_new_name);
            // chmod(public_path('images/'.$article_category.'_img'.$file_new_name), 0755);

            // edit article image
            $article = Article::where('id',strip_tags($request->id))->first();
            $article['image'] = $file_new_name;
            $article -> save();

$test_filename = '158208217_916347415858602_6227987547054177502_n_(2021-03-10-19-03-19).jpg';

            // open an image file
            $img = Image::make('images/'.$article->category.'_img/origin_img/'.$test_filename);

            // now you are able to resize the instance
            $img->resize(1024, 576);

            // finally we save the image as a new file
            $img->save(public_path('images/'.$article->category.'_img/'.$test_filename));

            dd($img);
        }
    }


    public function global_sector_validate($request)
    {
        $request->validate([
            'published' => 'required',
        ]);
    }
}

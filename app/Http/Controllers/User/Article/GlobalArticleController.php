<?php

namespace App\Http\Controllers\User\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;

class GlobalArticleController extends Controller
{
    public function add_global_article(Request $request)
	{
        $request->user()->authorizeRoles(['manager', 'admin']);
        // dd(1);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $us_articl = Us_article::get();
            foreach ($us_articl as $us) {
                $last_us_article_id = $us->id;
            }

            $ka_articl = Ka_article::get();
            foreach ($ka_articl as $ka) {
                $last_ka_article_id = $ka->id;
            }

            $ru_articl = Ru_article::get();
            foreach ($ru_articl as $ru) {
                $last_ru_article_id = $ru->id;
            }
            
            // dd($last_ru_article_id, $last_ka_article_id, $last_us_article_id);

            $article = new Article();

            $article['category']=$request->category;
            $article['published']=$request->published;
            $article['completed']=$request->completed; 
            $article['map']=$request->map;
            $article['weather']=$request->weather; 

            $article['start_data_day']=$request->start_data_day;
            $article['and_data_day']=$request->and_data_day;
            $article['and_data_month']=$request->and_data_month;
            $article['start_data_month']=$request->start_data_month;

            $article['fb_link']=$request->fb_link;
            $article['twit_link']=$request->twit_link; 
            $article['google_link']=$request->google_link;
            $article['inst_link']=$request->inst_link;
            $article['web_link']=$request->web_link;

            // $article['us_article_id']=$last_us_article_id+1;
            // $article['ru_article_id']=$last_ru_article_id+1;
            // $article['ka_article_id']=$last_ka_article_id+1;

            $article['us_article_id']=$last_us_article_id;
            $article['ru_article_id']=$last_ru_article_id;
            $article['ka_article_id']=$last_ka_article_id;

            $article -> save();

            $last_ru_article_id = 0;
            $last_us_article_id = 0;
            $last_ka_article_id = 0;
        }
        
        
    }



    public function edit_global_article(article $article, Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
            
            $global_article = Article::find($request->id);

            $global_article->published = $request->published;
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

        if ($request->hasFile('profile_pic'))
        {   
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $picture   = date('His').'-'.$filename;
            $file->move(public_path('images/articles_img/'), $picture);


            $global_article = Article::get();
            foreach ($global_article as $global) {
                $last_global_article_id = $global->id;
            }

            $article = Article::where('id',strip_tags($last_global_article_id))->first();

            // $article = new Article();

            $article['image']=$filename;
            $article -> save();

            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } 
        else
        {
            return response()->json(["message" => "Select image first."]);
        }
    }
}

<?php

namespace App\Http\Controllers\User\Article;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Ka_article;
use App\Models\Us_article;
use App\Models\Ru_article;
use App\Models\Comment;

use App\Services\ImageEditService;

use File;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);
        
    	if (view()->exists('user.data_table')) {
            if ($request->article_category == "outdoor") {
                // $articles = Article::latest('id')->where('category', '=', 'outdoor')->get();
                $title = 'Outdoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'outdoor';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "indoor") {
                // $articles = Article::latest('id')->where('category', '=', 'indoor')->get();
                $title = 'Indoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'indoor';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "ice") {
                // $articles = Article::latest('id')->where('category', '=', 'ice')->get();
                $title = 'Ice & Mixed';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'ice';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "news") {
                // $articles = Article::latest('id')->where('category', '=', 'news')->get();
                $title = 'News';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'news';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "other") {
                // $articles = Article::latest('id')->where('category', '=', 'other')->get();
                $title = 'Other';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'other';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "security") {
                // $articles = Article::latest('id')->where('category', '=', 'security')->get();
                $title = 'Security';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'security';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "partner") {
                // $articles = Article::latest('id')->where('category', '=', 'partner')->get();
                $title = 'Partners';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'partner';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "event") {
                // $articles = Article::latest('id')->where('category', '=', 'event')->get();
                $title = 'Events';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'event';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }

    		$data = [
    			'title'=>$title,
    			// 'table_1'=>$articles,
    		    'table_1_add_url'=>$articles_add_url,
                'table_1_add_category'=>$article_add_category,
        	    'table_1_edit_url'=>"/articles/edit/",
    		    'table_1_article_url'=>$article_page_utl,
    		    'table_1_title'=>'1',
    		    'table_1_pablic' => '',
                'table_1_name'=> $table_1_name,

                "table_1_get_route"=>"/articles/get_article_data/",
                'table_1_del'=>"/articles/del/",
    		    
    		    'page_name' => $page_name,
    		    'active' => 'Outdoor',
    		    'page_route' => 'outdoor_page',
            ];
            return view('user.data_table',$data);
    	}
        abort(404);	
    }


    public function get_article_data(Article $article, Request $request)
    {
        return Article::latest('id')->where('category', '=', $request->article_category)->get();
    }


	public function add_global_article(Request $request)
	{
        $request->user()->authorizeRoles(['manager', 'admin']);
        
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
            
            dd($last_ru_article_id, $last_ka_article_id, $last_us_article_id);

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
        
        if (view() -> exists('user.components.forms.article_add_form')) {
            $category = $request->category;
            $data=[
                'category' => $category,
                'title' => 'New '.$category,
                'back_btn' => 'home',
                'add_title' => 'Add '.$category,
                'add_active' => 'Add '.$category,
                
                'add_form' => 'articleAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.article_add_form', $data);
        }
        abort(404);
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



	public function edit_global_article(article $article, Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        // if ($request->isMethod('delete')) {
        //     $article ->delete();
        //     return back()->with('status', 'article delited'); //text
        // }

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
    
            // $validator = validator::make($input, [
            //     'title' => 'required|max:190',
            //     'text' => 'max:500'
            // ]);
            // if ($validator->fails()) {
            //     return back() -> withErrors($validator) -> withInput();
            // }

            // if ($request->hasFile('image')) {
            //     $file = $request->file('image');
            //     $file -> move(public_path().'/assets/img/article_img/',$file->getClientOriginalName());
            //     $input['image'] = $file->getClientOriginalName();
            // }
            // else {
            //     $input['image'] = $input['old_image'];
            // }

            // unset($input ['old_image']);
            $article -> fill($input);

            if ($article->update()) {
                return redirect('user/article')->with('status','article updated'); //text
            }
        }

        $old = $article -> toArray();

        if (view()->exists('user.components.forms.article_edit_form')) {
            $data = [
                // 'title' => 'Edit article - '.$old['title'],
                'title' => 'Edit title',
                'data' => $old,
                'back_btn' => 'home',
                'edit_title' => 'Edit article',
                'edit_active' => 'Edit article',
                
                'edit_form' => 'home',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.article_edit_form', $data);
        }
    }

    public function delete(Article $article, Request $request)
	{
		if ($request->isMethod('post')) {
            $global_id=$request->id;

            $global_article = Article::where('id',strip_tags($global_id))->first();
            $us_article = Us_article::where('id',strip_tags($global_article->us_article_id))->first();
            $ru_article = Ru_article::where('id',strip_tags($global_article->ru_article_id))->first();
            $ka_article = Ka_article::where('id',strip_tags($global_article->ka_article_id))->first();

            // dd($global_article);

            // delete product file
            // $fileName = $არტიცლე['image'];
            // $destinationPath = 'images/shop_img/';
            // File::delete($destinationPath.$fileName);

            // delete product from db
            $global_article ->delete();
            $us_article ->delete();
            $ru_article ->delete();
            $ka_article ->delete();
        }
    }
}

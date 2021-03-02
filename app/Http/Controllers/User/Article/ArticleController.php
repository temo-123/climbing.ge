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

use App\Services\imageControllService;

use File;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);
        
    	if (view()->exists('user.data_table')) {
            if ($request->article_category == "outdoor") {
                $title = 'Outdoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'outdoor';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "indoor") {
                $title = 'Indoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'indoor';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "ice") {
                $title = 'Ice & Mixed';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'ice';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "news") {
                $title = 'News';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'news';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "other") {
                $title = 'Other';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'other';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "security") {
                $title = 'Security';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'security';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "partner") {
                $title = 'Partners';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'partner';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            elseif ($request->article_category == "event") {
                $title = 'Events';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'event';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            
    		$data = [
    			'title'=>$title,
    			// 'table_1'=>$articles,
    		    'table_1_add_url'=>$articles_add_url,
                'table_1_add_category'=>$article_add_category,
        	    'table_1_edit_url'=>"/articles/edit/",
    		    'table_1_article_url'=>$article_page_url,
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


    
    function create_temporary_article(Request $request)
    {
        $article_us = new Us_article();
        $article_us['title']="Us temporary article";
        $article_us -> save();

        $article_ka = new Ka_article();
        $article_ka['title']="Ka temporary article";
        $article_ka -> save();

        $article_ru = new Ru_article();
        $article_ru['title']="Ru temporary article";
        $article_ru -> save();

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

        $article = new Article();
        $article['url_title'] = 'temporary_article';
        $article['category']=$request->category;
        $article['published']=0;
        $article['completed']=1; 

        $article['us_article_id']=$last_us_article_id;
        $article['ru_article_id']=$last_ru_article_id;
        $article['ka_article_id']=$last_ka_article_id;

        $article -> save();
    }
    public function get_temporary_article_editing_data()
    {
        $temporary_article = Article::where("url_title","=","temporary_article")->get();
        foreach ($temporary_article as $article) {
            $last_temporary_article_id = $article->id;
        }

        return(
            $data = [
                "last_temporary_article_id" => $last_temporary_article_id,
            ]
        );
    }



    public function get_editing_data(Request $request)
    {
        $global_article = Article::where('id',strip_tags($request->id))->first();
        $us_article = Us_article::where('id',strip_tags($global_article->us_article_id))->first();
        $ka_article = Ka_article::where('id',strip_tags($global_article->ka_article_id))->first();
        $ru_article = Ru_article::where('id',strip_tags($global_article->ru_article_id))->first();
        // dd($global_article->us_article_id);
        return(
            $data = [
                "global_article" => $global_article,
                "us_article" => $us_article,
                "ka_article" => $ka_article,
                "ru_article" => $ru_article,
            ]
        );
        
    }


    public function add_article_page(Request $request)
    {
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

    public function edit_article_page(article $article, Request $request)
    {
        $old = $article -> toArray();

        $global_article_categoyr = Article::where('id',strip_tags($request->id))->get();
        foreach ($global_article_categoyr as $article_category) {
            $category = $article_category->category;
        }
        if (view()->exists('user.components.forms.article_edit_form')) {
            $data = [
                'category' => $category,
                // 'title' => 'Edit article - '.$old['title'],
                "editing_article_id" => $request->id,
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
            // dd($data);
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
            
            // delete article file
            imageControllService::image_delete($global_article->category.'_img', $global_article, $request);

            // delete article from db
            $global_article ->delete();
            $us_article ->delete();
            $ru_article ->delete();
            $ka_article ->delete();
        }
    }
}

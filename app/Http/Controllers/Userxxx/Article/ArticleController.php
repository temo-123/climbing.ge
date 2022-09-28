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

use App\Services\ImageControllService;

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
            elseif ($request->article_category == "mount_route") {
                $title = 'Mount routes';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'mount_route';
                $articles_edit_url = 'articleEdit';
                $article_page_url = 'index';
            }
            
            // dd($request->article_category);
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
    }


    
    function create_temporary_article(Request $request)
    {
        
    }
    public function get_temporary_article_editing_data()
    {
        
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
            ImageControllService::image_delete('images/'.$global_article->category.'_img/', $global_article, 'image');
            ImageControllService::image_delete('images/region_sectors_img/', $global_article, 'climbing_area_image');

            // delete article from db
            $global_article ->delete();
            $us_article ->delete();
            $ru_article ->delete();
            $ka_article ->delete();
        }
    }
}
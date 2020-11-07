<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;

use App\Services\GetArticlesService;

class ArticlesController extends Controller
{
    public function index(Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);
        
    	if (view()->exists('user.article_list')) {
            if ($request->article_category == "outdoor") {
                $articles = Article::latest('id')->where('category', '=', 'outdoor')->get();
                $title = 'Outdoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'outdoor';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "indoor") {
                $articles = Article::latest('id')->where('category', '=', 'indoor')->get();
                $title = 'Indoor';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'indoor';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "ice") {
                $articles = Article::latest('id')->where('category', '=', 'ice')->get();
                $title = 'Ice & Mixed';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'ice';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "news") {
                $articles = Article::latest('id')->where('category', '=', 'news')->get();
                $title = 'News';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'news';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "other") {
                $articles = Article::latest('id')->where('category', '=', 'other')->get();
                $title = 'Other';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'other';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "security") {
                $articles = Article::latest('id')->where('category', '=', 'security')->get();
                $title = 'Security';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'security';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "partner") {
                $articles = Article::latest('id')->where('category', '=', 'partner')->get();
                $title = 'Partners';
                $page_name = $title;
                $table_1_name = $title;
                $articles_add_url = 'articleAdd';
                $article_add_category = 'partner';
                $articles_edit_url = 'articleEdit';
                $article_page_utl = 'index';
            }
            elseif ($request->article_category == "event") {
                $articles = Article::latest('id')->where('category', '=', 'event')->get();
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
    			'table_1'=>$articles,

    		    'table_1_add_url'=>$articles_add_url,
                'table_1_add_category'=>$article_add_category,
        	    'table_1_edit_url'=>$articles_edit_url,
    		    'table_1_article_url'=>$article_page_utl,
    		    'table_1_title'=>'1',
    		    'table_1_pablic' => '',
    		    'table_1_name'=> $table_1_name,
    		    
    		    'page_name' => $page_name,
    		    'active' => 'Outdoor',
    		    'page_route' => 'outdoor_page',
            ];
    		return view('user.article_list',$data);
    	}
    	abort(404);	}

	public function add(Request $request)
	{
        $request->user()->authorizeRoles(['manager', 'admin']);
        // dd($request->category);
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            // $validator = validator::make($input, [
            //     'title' => 'required|max:190',
            //     'image' => 'required',
            //     'text' => 'max:500',
            // ]);
            // if ($validator->fails()) {
            //     return back() -> withErrors($validator) -> withInput();
            // }
dd($request);
            if ($request->hasFile('image')) {
                
                $file = $request -> file('image');
                $input['image'] = $file -> getClientOriginalName();

                $file -> move(public_path().'/assets/img/article_img/', $input['image']);
            }
            
            $article = new Article();
            $article['category']=$request->category;
            $article -> fill($input);
            // dd($article);

            if ($article->save()) {
                return redirect(url()->previous()) -> with('status', $request->category.' addid'); //text
            }
        }
        
        if (view() -> exists('user.components.forms.article_form')) {
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
            return view('user.components.forms.article_form', $data);
        }
        abort(404);
	}

	public function edit(article $article, Request $request)
    {
        
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request->isMethod('delete')) {
            $article ->delete();
            return back()->with('status', 'article delited'); //text
        }

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');
    
            // $validator = validator::make($input, [
            //     'title' => 'required|max:190',
            //     'text' => 'max:500'
            // ]);
            // if ($validator->fails()) {
            //     return back() -> withErrors($validator) -> withInput();
            // }

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $file -> move(public_path().'/assets/img/article_img/',$file->getClientOriginalName());
                $input['image'] = $file->getClientOriginalName();
            }
            else {
                $input['image'] = $input['old_image'];
            }

            unset($input ['old_image']);
            $article -> fill($input);

            if ($article->update()) {
                return redirect('user/article')->with('status','article updated'); //text
            }
        }

        $old = $article -> toArray();

        if (view()->exists('admin.components.form')) {
            $data = [
                'title' => 'Edit article - '.$old['title'],
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
            return view('admin.components.form', $data);
        }
	}

	public function delete()
	{
		# code...
	}
}

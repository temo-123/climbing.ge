<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Article;
use App\Comment;
use App\Gallery;

class TechTipsController extends Controller
{
	public function security_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.teachtips')) {
            $security = Article::where('category', '=', 'security')->where('url_title',strip_tags($name))->first();
            $security_id = $security->id;
            
            $other_list = Article::where('category', '=', 'security')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            
            $comments = Comments::where('article_id',strip_tags($security_id))->get();
            
            $article = $security;
            
            $article_gallery = Gallery::where('article_id',strip_tags($security_id))->get();


            $data  = [
                'title'=>$security->name,
                'article'=>$security,

                'articles_gallery'=>$article_gallery,
                'all_article_but'=>'index',
                'article_map'=>'News',
                
                'image_dir' => 'security_img',
                
                'article_edit_link'=>'securityEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'security_page',
                'other_article_img'=>'assets/img/security_img/',

                'comments'=>$comments,
                
                'article'=>$article
            ];

            return view('site.teachtips',$data);
        }
        else{
            abort(404);
        }
 	}
}

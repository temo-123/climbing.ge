<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Gallery;

class NewsController extends Controller
{
	public function news_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.news_page')) {
            $news = Article::where('url_title',strip_tags($name))->first();
            
            $article = $news;
            $news_id = $news->id;
            
            $comments = Comment::where('article_id','=',$news_id)->get();
            $article_gallery = Gallery::where('article_id',strip_tags($news_id))->where('category','=','article_image')->limit(8)->get();
            $other_list = Article::inRandomOrder()->where('category','=','news')->where('published','=','1')->limit(6)->get();

            $data  = [
                'title'=>$news->name,
                'article'=>$news,

                'articles_gallery'=>$article_gallery,
                'slider_link'=>'../../assets/img/news_img/slider_img/',
                'all_article_but'=>'index',
                'article_map'=>'News',
                
                'image_dir' => 'nevs_img',
                
                'article_edit_link'=>'articlesEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'news_page',
                'other_article_img'=>'images/news_img/',

                'comments'=>$comments,
                
                'article'=>$article
            ];

            return view('site.news_page',$data);
        }
        else{
            abort(404);
        }
    }
}

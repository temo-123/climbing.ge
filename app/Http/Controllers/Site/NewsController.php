<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Gallery;

use GetArticlesService;

class NewsController extends Controller
{
	public function news_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.standart_article_page')) {
            $global_news = Article::where('url_title',strip_tags($name))->first();

            $news_id = $global_news->id;
            
            $news = GetArticlesService::get_locale_article_in_page($global_news);
            
            $comments = Comment::where('article_id','=',$news_id)->get();
            $article_gallery = Gallery::where('article_id',strip_tags($news_id))->where('category','=','article_image')->limit(8)->get();

            $global_other_list = Article::inRandomOrder()->where('category','=','news')->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);

            $data  = [
                'title'=>$news[0][0]['title'],
                'article'=>$news[0],
    			// 'other'=>1,
    			
                'image' => 'news_img/'.$news[0]['image'],
                
                'articles_gallery'=>$article_gallery,
                'comments'=>$comments,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'index',
                'article_map'=>'Other',
                
                // 'article_edit_link'=>'otherEdit',
                
                'other_article'=>$other_list,
                'other_article_link'=>'other_page',
                'other_article_img'=>'images/news_img/',
            ];

            return view('site.standart_article_page',$data);
        }
        else{
            abort(404);
        }
    }
}

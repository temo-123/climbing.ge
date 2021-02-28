<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Gallery;

use GetArticlesService;

class PartnersController extends Controller
{
    public function partners_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.standart_article_page')) {
            $global_partners = Article::latest('id')->where('category', '=', 'partner')->where('url_title',strip_tags($name))->first();
            
            $partners_id = $global_partners->id;

            $partners = GetArticlesService::get_locale_article_in_page($global_partners);
            
            $article_gallery = Gallery::where('article_id',strip_tags($partners_id))->limit(8)->get();
            $comments = Comment::where('article_id',strip_tags($partners_id))->get();

            $global_other_list = Article::inRandomOrder()->where('category','=','partner')->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);
                
            $data  = [
                'title'=>$partners[0][0]['title'],
                'article'=>$partners[0],
                
                'articles_gallery'=>$article_gallery,
                'comments'=>$comments,
                
                'slider_link'=>'gallery_img/partnersr_img/',
                'all_article_but'=>'about_us_page',
                'article_map'=>'partners',
                
                'image' => 'other_img/'.$partners[0]['image'],
                
                'article_edit_link'=>'partnersEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'partners_page',
                'other_article_img'=>'images/partners_img/',
                
                // 'article'=>$article
            ];
            return view('site.standart_article_page',$data);
        }
        else{
            abort(404);
        }
    }
}

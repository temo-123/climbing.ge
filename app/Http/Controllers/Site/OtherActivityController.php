<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Comment;

use App\Services\GetArticlesService;

class OtherActivityController extends Controller
{
    public function other_list()
    {
    	if (view()->exists('site.other_list')) {
    		$global_others = Article::latest('id')->where('category', '=', 'other')->where('published','=','1')->get();
            $article_count = Article::latest('id')->where('category', '=', 'other')->where('published','=','1')->count();

            $others = GetArticlesService::get_locale_article($global_others);
            $time_array = GetArticlesService::get_new_article_pin($others);

    		$data = [
    			'title'=>'Other Activity',
    			'article_list'=>$others,
                'article_count'=>$article_count,
    			'other'=>1,
                'time_array' => $time_array,
    			
                'num' => 1,
                'articles_link' => 'other_page',
                'image_dir' => 'other_img',
                
                // Meta teg
                'meta_title' => 'Outdoor climbing regions in Georgia',
                'meta_description' => 'In Georgia are many interesting activity. You can see the full information about these regions and visit one of them.',
                'meta_img' => 'outdoor.jpg'
    		];
    		return view('site.other_list',$data);
    	}
    	abort(404);
    }

    public function other_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.standart_article_page')) {
            $global_others = Article::latest('id')->where('category', '=', 'other')->where('url_title',strip_tags($name))->first();
            $other_id = $global_others->id;

            $other = GetArticlesService::get_locale_article_in_page($global_others);
            
            $article_gallery = Gallery::where('article_id',strip_tags($other_id))->limit(8)->get();
            $comments = Comment::where('article_id',strip_tags($other_id))->get();

            $global_other_list = Article::where('category', '=', 'other')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);

            $data  = [
                'title'=>$other[0][0]['title'],
                'article'=>$other[0],
    			'other'=>1,
    			
                'image' => 'other_img/'.$other[0]['image'],
                
                'articles_gallery'=>$article_gallery,
                'comments'=>$comments,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'other_list',
                'article_map'=>'Other',
                
                'article_edit_link'=>'otherEdit',
                
                'other_article'=>$other_list,
                'other_article_link'=>'other_page',
                'other_article_img'=>'images/other_img/',
            ];

            return view('site.standart_article_page',$data);
        }
        else{
            abort(404);
        }
    }
}

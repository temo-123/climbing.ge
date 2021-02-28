<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Gallery;

use App\Services\GetArticlesService;

class IceController extends Controller
{
    public function ice_list()
    {
    	if (view()->exists('site.ice_list')) {
            $global_ices = Article::where('category', '=', 'ice')->where('published', '=', 1)->get();
            $article_count = Article::where('category', '=', 'ice')->where('published', '=', 1)->count(); 

            $ices = GetArticlesService::get_locale_article($global_ices);

            $time_array = GetArticlesService::get_new_article_pin($ices);
            
    		$data = [
    			'title'=>'Ice And Mixed',
    			'article_list'=>$ices,
    			'article_count'=>$article_count,
    			'ice'=>1,
                'time_array' => $time_array,
    			
    			'num'=>1,
                'articles_link' => 'ice_page',
                'image_dir' => 'ice_img',
                
                // Meta teg
                'meta_title' => 'Outdoor climbing regions in Georgia',
                'meta_description' => 'In Georgia are many outdoor climbing zone. You can see the full information about these regions and visit one of them.',
                'meta_img' => 'ice.jpg'
    		];
    		return view('site.ice_list',$data);
    	}
    	abort(404);
    }

    public function ice_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.standart_article_page')) {

            // $locale = request()->segment(1, '');

            $global_ice = Article::where('category', '=', 'ice')->where('url_title',strip_tags($name))->first();
            $ice_id = $global_ice->id;
            
            $locale_ice = GetArticlesService::get_locale_article_in_page($global_ice);

            $ice = $locale_ice[0];

            $article_gallery = Gallery::where('article_id',strip_tags($ice_id))->limit(8)->get();
            $comments = Comment::where('article_id',strip_tags($ice_id))->get();

            $global_other_list = Article::where('category', '=', 'ice')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);
            
            $data  = [
    			'title'=>'Ice And Mixed',
                'article'=>$ice,
                'ice'=>1,
                
                'articles_gallery'=>$article_gallery,
                
                'slider_link'=>'../../images/ice_img/slider_img/',
                'all_article_but'=>'ice_list',
                'article_map'=>'ice',
                'image' => 'ice_img/'.$global_ice->image,

                'article_edit_link'=>'articlesEdit',
                
                'other_article'=>$other_list,
                'other_article_link'=>'ice_page',
                'other_article_img'=>'images/ice_img/',
                
                'comments'=>$comments,
                
                // 'article'=>$article
            ];

            return view('site.standart_article_page', $data);
        }
        else {
            abort(404);
        }
    }
}

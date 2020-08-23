<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Gallery;
use Carbon\Carbon;

class IceController extends Controller
{
    public function ice_list()
    {
    	if (view()->exists('site.mount_list')) {
    		$ices = Article::where('category', '=', 'ice')->where('published', '=', 1)->get();
    		$article_count = Article::where('category', '=', 'ice')->where('published', '=', 1)->count();

            // $time_array = array();
            // foreach ($ices as $ice) {
            //     if ($ice->created_at->lt(Carbon::now()->subDays(30))){
            //         $time = 0;
            //         array_push($time_array, ['id'=>$ice->id, 'name'=>$ice->title, 'time'=>$time]);
            //     }
            //     else {
            //         $time = 1;
            //         array_push($time_array, ['id'=>$ice->id, 'name'=>$ice->title, 'time'=>$time]);
            //     }
            // }

    		$data = [
    			'title'=>'Ice And Mixed',
    			'article_list'=>$ices,
    			'article_count'=>$article_count,
    			'ice'=>'',
                // 'time_array' => $time_array,
    			
    			'num'=>1,
                'articles_link' => 'ice_page',
                'image_dir' => 'ice_img',
                
                // Meta teg
                'meta_title' => 'Outdoor climbing regions in Georgia',
                'meta_description' => 'In Georgia are many outdoor climbing zone. You can see the full information about these regions and visit one of them.',
                'meta_img' => 'ice.jpg'
    		];
    		return view('site.mount_list',$data);
    	}
    	abort(404);
    }

    public function ice_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.ice_page')) {
        
            $ice = Article::where('category', '=', 'ice')->where('url_title',strip_tags($name))->first();
            $ice_id = $ice->id;
            
            $article_gallery = Gallery::where('article_id',strip_tags($ice_id))->limit(8)->get();
            $comments = Comment::where('article_id',strip_tags($ice_id))->get();
            $other_list = Article::where('category', '=', 'ice')->inRandomOrder()->where('published','=','1')->limit(6)->get();

            $data  = [
                'title'=>$ice,
                'article'=>$ice,
                'ice'=>1,
                
                'articles_gallery'=>$article_gallery,
                
                'slider_link'=>'../../images/ice_img/slider_img/',
                'all_article_but'=>'ice_list',
                'article_map'=>'ice',
                'image_dir' => 'ice_img',

                'article_edit_link'=>'articlesEdit',
                
                'other_article'=>$other_list,
                'other_article_link'=>'ice_page',
                'other_article_img'=>'assets/img/ice_img/',
                
                'comments'=>$comments,
                
                // 'article'=>$article
            ];

            return view('site.ice_page', $data);
        }
        else {
            abort(404);
        }
    }
}

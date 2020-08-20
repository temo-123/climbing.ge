<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Gallery;
use App\Models\Comment;
use Carbon\Carbon;

class OtherActivityController extends Controller
{
    public function other_list()
    {
    	if (view()->exists('site.other_list')) {
    		$others = Article::latest('id')->where('category', '=', 'other')->where('published','=','1')->get();
            $article_count = Article::latest('id')->where('category', '=', 'other')->where('published','=','1')->count();

            $time_array = array();
            // foreach ($others as $other) {
            //     if ($other->created_at->lt(Carbon::now()->subDays(30))){
            //         $time = 0;
            //         array_push($time_array, ['id'=>$other->id, 'name'=>$other->title, 'time'=>$time]);
            //     }
            //     else {
            //         $time = 1;
            //         array_push($time_array, ['id'=>$other->id, 'name'=>$other->title, 'time'=>$time]);
            //     }
            // }

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
        if (view()->exists('site.other_page')) {
            $other = Article::latest('id')->where('category', '=', 'other')->where('url_title',strip_tags($name))->first();
            $other_id = $other->id;
            
            $article_gallery = Galleries::where('article_id',strip_tags($other_id))->limit(8)->get();
            $comments = Comments::where('article_id',strip_tags($other_id))->get();
            $other_others_list = Article::latest('id')->where('category', '=', 'other')->latest('id')->limit(6)->inRandomOrder();

            // $other_outdoors_list = Article::latest('id')->where('category', '=', 'other')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            
            $data  = [
                'title'=>$other->title,
                'article'=>$other,
    			'other'=>1,
    			
                'image_dir' => 'other_img',
                
                'articles_gallery'=>$article_gallery,
                'comments'=>$comments,
                
                'slider_link'=>'../../assets/img/other_img/slider_img/',
                'all_article_but'=>'other_list',
                'article_map'=>'Other',
                
                'article_edit_link'=>'otherEdit',
                
                'other_article'=>$other_others_list,
                'other_article_link'=>'other_page',
                'other_article_img'=>'assets/img/other_img/',
            ];

            return view('site.other_page',$data);
        }
        else{
            abort(404);
        }
    }
}

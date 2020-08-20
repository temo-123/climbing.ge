<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount;
use Carbon\Carbon;

class MountController extends Controller
{
    public function mount_list()
    {
    	if (view()->exists('site.mount_list')) {
    		$mounts = Article::latest('id')->where('category', '=', 'mount_routes')->where('published', '=', 1)->get();
    		$article_count = Article::latest('id')->where('category', '=', 'mount_routes')->where('published', '=', 1)->count();
            
            $time_array = array();
            foreach ($mounts as $mount) {
                if ($mount->created_at->lt(Carbon::now()->subDays(30))){
                    $time = 0;
                    array_push($time_array, ['id'=>$mount->id, 'name'=>$mount->title, 'time'=>$time]);
                }
                else {
                    $time = 1;
                    array_push($time_array, ['id'=>$mount->id, 'name'=>$mount->title, 'time'=>$time]);
                }
            }
            // dd($time_array);

            $tags = Mount::distinct()->get();
            $tags_count = Mount::distinct()->count();
            $article_num=0;
    		$data = [
    			'title'=>'Mountaineering',
    			'article_list'=>$mounts,
    			'article_count' => $article_count,
    			'tags_count' => $tags_count,
                'time_array' => $time_array,
    			
    			'image_dir'=>'mount_route_img',
    			'articles_link' => 'mount_page',
                // 'articles_link' => 'ice_page',
    			
    			'article_num'=> $article_num,

                'tags' => $tags,

    			'mount'=>$mounts,
    			
    			'num'=>1,
    			
    			 // Meta teg
                'meta_title' => 'Mountaineering In Georgia',
                'meta_description' => 'In Georgia are many mountaineering route. You can see the full information about these regions and visit one of them.',
                'meta_img' => 'mount.jpg'
    		];
    		return view('site.mount_list',$data);
    	}
    	abort(404);
    }

    public function mount_page($name){
        if (!$name) {
            abort(404);
        }
        if (view()->exists('site.mount_page')) {
            $mount_routes = Article::where('category', '=', 'mount_routes')->where('url_title',strip_tags($name))->first();

            $mount_route_id = $mount_routes->mount_id;
            $mounts_system = Mount::all();
        
            $mounts_system = Mount::where('id','=',$mount_route_id)->get();
            $comments = \App\Comments::where('article_id',strip_tags($mount_route_id))->get();
            $other_list = Article::inRandomOrder()->where('category', '=', 'mount_routes')->where('published','=','1')->limit(6)->get();

            $data = [
                'title'=>$mount_routes,
                'article'=>$mount_routes,
                'mounts_system'=>$mounts_system,
    			'mount'=>1,

    			'image_dir'=>'mount_route_img',
                'all_article_but'=>'mount_list',
                'article_map'=>'mount',
                
                'article_edit_link'=>'mountEdit',

                'other_article'=>$other_list,
                'other_article_link'=>'mount_page',
                'other_article_img'=>'images/mount_route_img/',
                
                'comments'=>$comments,
            ];

            return view('site.mount_page', $data);
        }
        else {
            abort(404);
        }
    }
}

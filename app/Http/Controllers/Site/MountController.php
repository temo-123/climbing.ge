<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount;
use App\Models\Comment;

use App\Services\GetArticlesService;

class MountController extends Controller
{
    public function mount_list()
    {
    	if (view()->exists('site.mount_list')) {
    		$global_mounts = Article::latest('id')->where('category', '=', 'mount_route')->where('published', '=', 1)->get();
            $article_count = Article::latest('id')->where('category', '=', 'mount_route')->where('published', '=', 1)->count();
            
            $mounts = GetArticlesService::get_locale_article($global_mounts);
            $time_array = GetArticlesService::get_new_article_pin($mounts);

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
            $global_mount_routes = Article::latest('id')->where('category', '=', 'mount_route')->where('url_title',strip_tags($name))->first();
            // dd($global_mount_routes->us_article_id);
            $mount_route_id = $global_mount_routes->id;
            $mount_id = $global_mount_routes->mount_id;
            $mount_routes = GetArticlesService::get_locale_article_in_page($global_mount_routes);

            // $mounts_system = Mount::all();
            $mounts_system = Mount::where('id','=',$mount_id)->get();

            $global_other_list = Article::latest('id')->where('category', '=', 'outdoor')->inRandomOrder()->where('published','=','1')->limit(6)->get();
            $other_list = GetArticlesService::get_locale_article($global_other_list);

            $comments = Comment::where('article_id',strip_tags($mount_route_id))->get();

            $data = [
                'title'=>$mount_routes[0],
                'article'=>$mount_routes[0],
                'mounts_system'=>$mounts_system,
    			'mount'=>1,

                'image' => 'mount_route_img/'.$global_mount_routes->image,

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

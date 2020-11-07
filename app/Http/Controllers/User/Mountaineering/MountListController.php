<?php

namespace App\Http\Controllers\User\Mountaineering;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Mount;

class MountListController extends Controller
{
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        
        // latest('id')->
	    $articles = article::latest('id')->where('category', '=', 'mount_routes')->get();
		$count_article = article::count();
	    
		$mount_systems = mount::latest('id')->get();
		$count_mount_system = mount::count();
        
        if (view()->exists('user.article_list')) {
    	   
    		$data = [
                'title'=>' Mountaineering route & mount systems',
                
    			'table_1'=>$mount_systems,
    		    'table_1_name' => 'Mounts',
                'table_1_count' => $count_mount_system,
    		    // 'table_1_add_url'=>'mount_systemAdd',
    		    // 'table_1_edit_url'=>'mount_systemEdit',
                
                'table_2' => $articles,
    		    'table_2_name' => 'Mount route',
                'table_2_count' => $count_article,
    		    // 'table_2_add_url'=>'articleAdd',
    		    // 'table_2_edit_url'=>'articleEdit',

    		    'page_name' => 'Mount And Mount Route',
    		    'active' => 'articles and mount_systems',
    		];
    		return view('user.article_list',$data);
    	}
        abort(404);
    }
}

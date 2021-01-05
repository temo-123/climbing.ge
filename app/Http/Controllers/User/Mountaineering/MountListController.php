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
        
        if (view()->exists('user.data_table')) {
    	   
    		$data = [
                'title'=>' Mountaineering route & mount systems',
				
    		    'table_1_name' => 'Mount routes',
                'table_1_del' => "/articles/del/",
				"table_1_add_url" => "articleAdd",
        	    'table_1_edit_url'=>"/articles/edit/",
                'table_1_add_category'=> 'mount_route',
                "table_1_get_route" => "/mountaineering/get_mount_route_data/",
				
    		    'table_2_name' => 'Mounts',
				'table_2_del'=>"/mountaineering/mount_delete/",
				"table_2_add_url" => "mountAddForm",
        	    'table_2_edit_url'=>"/mountaineering/mount_edit_form/",
                "table_2_get_route"=>"/mountaineering/get_mount_data/",

    		    'page_name' => 'Mount And Mount Route',
    		    'active' => 'articles and mount_systems',
    		];
    		return view('user.data_table',$data);
    	}
        abort(403);
	}
	
	public function get_mount_data()
	{
        return Mount::latest('id')->get();
	}

	public function get_mount_route_data()
	{        
		return Article::where('category', '=', 'mount_route')->get();
	}
}

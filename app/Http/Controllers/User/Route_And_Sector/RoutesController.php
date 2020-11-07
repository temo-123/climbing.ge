<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;

class RoutesController extends Controller
{
    public function add(Request $request)
    {
    	$request->user()->authorizeRoles(['manager', 'admin']);

        
        if ($request -> isMethod('post')) {
        dd ($request -> except('_token'));
            $input = $request -> except('_token');  
            
            $route = new route();
            $route -> fill($input);

            if ($route->save()) {
                return redirect('user/routes_and_sectors') -> with('status', 'route addid'); //text
            }
        }
    	if (view() -> exists('user.components.forms.routes_and_sectors.route.route_add')) {
    	    
            $tags = Sector::distinct()->get(['article_id']);
            
            $indoor_sectors = Sector::all();
            $route_images = Route::all();
            $mtps = Mtp::all();
            
    		$data=[
    			'title' => 'New route',
    			
    			'indoor_sectors'=>$indoor_sectors,
    			'route_images'=>$route_images,
    			'mtps'=>$mtps,
    			
    			'tags' => $tags
    		];
    		
    		
    		return view('user.components.forms.routes_and_sectors.route.route_add', $data);
    	}
    	abort(404);
    }

    public function edit()
    {
        echo "route edit";
    }
}

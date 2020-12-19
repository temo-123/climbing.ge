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
            $input = $request -> except('_token');
            $article = new Route();

            $article['sector_id']=$request->sector_id;
            $article['gread']=$request->gread;
            $article['or_gread']=$request->or_gread; 
            $article['name']=$request->name;
            $article['text']=$request->text; 
            $article['last_carabin']=$request->last_carabin;
            $article['height']=$request->height;
            $article['bolts']=$request->bolts;
            $article['bolter']=$request->bolter;
            $article['first_ascent']=$request->first_ascent;

            $article -> save();
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


    public function delete(Request $request)
    {
        if ($request->isMethod('post')) {
            $sector_id=$request->id;

            $sector = Route::where('id',strip_tags($sector_id))->first();

            // dd($sector);

            // delete product file
            // $fileName = $არტიცლე['image'];
            // $destinationPath = 'images/shop_img/';
            // File::delete($destinationPath.$fileName);

            // delete product from db
            $sector ->delete();
        }
    }
}

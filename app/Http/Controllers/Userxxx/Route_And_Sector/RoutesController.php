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
    	// $request->user()->authorizeRoles(['manager', 'admin']);

        // if ($request -> isMethod('post')) {
        //     $this->route_validate($request);
            
        //     $sector_route_count = Route::where('sector_id',strip_tags($request->sector_id))->count();
        //     $new_route_num = $sector_route_count+1;

        //     $article = new Route();

        //     $category = $this->get_route_category($request);

        //     $article['category']=$category;
        //     $article['sector_id']=$request->sector_id;
        //     $article['num']=$new_route_num;
        //     $article['grade']=$request->grade;
        //     $article['or_grade']=$request->or_grade; 
        //     $article['name']=$request->name;
        //     $article['text']=$request->text; 
        //     $article['last_carabin']=$request->last_carabin;
        //     $article['height']=$request->height;
        //     $article['bolts']=$request->bolts;
        //     $article['bolter']=$request->bolter;
        //     $article['bolting_data']=$request->bolting_data;
        //     $article['first_ascent']=$request->first_ascent;

        //     $article -> save();
        // }

    	// if (view() -> exists('user.components.forms.routes_and_sectors.route.route_add')) {
        //     $tags = Sector::distinct()->get(['article_id']);
            
        //     $indoor_sectors = Sector::all();
        //     $route_images = Route::all();
        //     $mtps = Mtp::all();
            
    	// 	$data=[
    	// 		'title' => 'New route',
    			
    	// 		'indoor_sectors'=>$indoor_sectors,
    	// 		'route_images'=>$route_images,
    	// 		'mtps'=>$mtps,
    			
    	// 		'tags' => $tags
    	// 	];
    		
    	// 	return view('user.components.forms.routes_and_sectors.route.route_add', $data);
    	// }
    	// abort(404);
    }

    public function edit(Request $request)
    {
        // $request->user()->authorizeRoles(['manager', 'admin']);

        // if ($request -> isMethod('post')) {
        //     $this->route_validate($request);

        //     $route = route::find($request->id);

        //     $category = $this->get_route_category($request);

        //     $route->category = $category;
        //     $route->sector_id = $request->sector_id;
        //     $route->grade = $request->grade;
        //     $route->or_grade = $request->or_grade; 
        //     $route->name = $request->name;
        //     $route->text = $request->text; 
        //     $route->last_carabin = $request->last_carabin;
        //     $route->height = $request->height;
        //     $route->bolts = $request->bolts;
        //     $route->bolter = $request->bolter;
        //     $route->bolting_data = $request->bolting_data;
        //     $route->first_ascent = $request->first_ascent;
            
        //     $route->update();
        // }
    }

    public function edit_form(Request $request)
    {
        // if (view()->exists('user.components.forms.routes_and_sectors.route.route_edit')) {
    	// 	$data = [
    	// 		'name' => 'Edit sector',
        //         "editing_route_id" => $request->id,
    	// 		// 'data' => $old,
    	// 	];

    	// 	return view('user.components.forms.routes_and_sectors.route.route_edit', $data);
        // }
    }

    // public function get_route_editing_data(Request $request)
    // {
    //     $route = Route::where('id',strip_tags($request->id))->first();
    //     return(
    //         $data = [
    //             "route" => $route,
    //         ]
    //     );
    // }

    public function delete(Request $request)
    {
        if ($request->isMethod('post')) {
            // $route_id=$request->id;

            // $route = Route::where('id',strip_tags($route_id))->first();

            // // dd($route);

            // // delete product file
            // // $fileName = $არტიცლე['image'];
            // // $destinationPath = 'images/shop_img/';
            // // File::delete($destinationPath.$fileName);

            // // delete product from db
            // $route ->delete();
        }
    }

//     public function routes_sequence(Request $request)
//     {
//         $route_num = 0;
//         foreach ($request->routes_sequence as $route) {
//             $route_id = $route['id'];
//             $route = Route::where('id',strip_tags($route_id))->first();
//             $route_num++;
//             $route['num'] = $route_num;
//             $route->update();
//         }
        
//         $mtp_num=0;
//         foreach ($request->mtp_sequence as $mtp) {
//             $mtp_id = $mtp['id'];
//             $mtp = mtp::where('id',strip_tags($mtp_id))->first();
//             $mtp_num++;
//             $mtp['num'] = $mtp_num;
//             $mtp->update();
//         }
//     }

//     private function route_validate($request)
//     {
//         $request->validate([
//             'name' => 'required|max:190',
//             'grade' => 'required',
//             'sector_id' => 'required',
//         ]);
//     }

//     public function get_route_category($request)
//     {
//         $category = 0;

//         if ($request->route_type == 'bouldering') {
//             $category = 4;
//         }
//         elseif ($request->route_type == 'top') {
//             $category = 2;
//         }
//         elseif ($request->route_type == 'tred') {
//             $category = 3;
//         }
//         else{
//             $category = 1;
//         }

//         return $category;
//     }
// }
}
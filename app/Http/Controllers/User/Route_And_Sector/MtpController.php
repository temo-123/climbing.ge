<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;

class MtpController extends Controller
{
    public function add_mtp(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            // $validator = validator::make($input, [
            //     'name' => 'required|max:190'
            // ]);
            // if ($validator->fails()) {
            //     return redirect() -> route('routes_and_sectors') -> withErrors($validator) -> withInput();
            // }   
            
            $route = new route();
            $route -> fill($input);

            if ($route->save()) {
                return redirect('user/routes_and_sectors') -> with('status', 'route addid'); //text
            }
        }
    	if (view() -> exists('user.components.forms.routes_and_sectors.mtp.mtp_add')) {
    	    
            $tags = Sector::distinct()->get(['region']);
            
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
    		
    		
    		return view('user.components.forms.routes_and_sectors.mtp.mtp_add', $data);
    	}
    	abort(404);
    }

    public function edit_mtp()
    {
        echo "edit mtp";
    }




    public function add_pitch(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            // $validator = validator::make($input, [
            //     'name' => 'required|max:190'
            // ]);
            // if ($validator->fails()) {
            //     return redirect() -> route('routes_and_sectors') -> withErrors($validator) -> withInput();
            // }   
            
            $route = new route();
            $route -> fill($input);

            if ($route->save()) {
                return redirect('user/routes_and_sectors') -> with('status', 'route addid'); //text
            }
        }
    	if (view() -> exists('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_add')) {
    	    
            $tags = Sector::distinct()->get(['region']);
            
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
    		
    		
    		return view('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_add', $data);
    	}
    	abort(404);
    }

    public function edit_pitch()
    {
        echo "edit pitch";
    }
}

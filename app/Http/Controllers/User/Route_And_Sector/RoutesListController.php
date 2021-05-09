<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Route;
use App\Models\Sector;
use App\Models\Mtp;
use App\Models\Mtp_pitch;
use App\Models\Article;

class RoutesListController extends Controller
{
    public function index(Request $request)
	{
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        // latest('id')->
	    $routes = Route::latest('id')->get();
		$route_tags = Route::distinct()->get(['sector_id']);
		$count_route = Route::count();
	    
		$sectors = Sector::latest('id')->get();
		$sector_tags = Sector::distinct()->get(['article_id']);
		$count_sector = Sector::count();
		
		$mtps = Mtp::latest('id')->get();
		$mtp_tags = Mtp::distinct()->get(['sector_id']);
		$count_mtp = Mtp::count();

        $mtp_pitchs = Mtp_pitch::latest('id')->get();
        $mtp_pitch_tags = Mtp_pitch::distinct()->get(['mtp_id']);
        $count_mtp_pitchs = Mtp_pitch::count();
		
        $route_in_sector = Route::orderBy('num')->get();
        
        if (view()->exists('user.data_table')) {
    	   
    		$data = [
                'title'=>'mounts',
                
    			'table_1'=>$sectors,
    		    'table_1_name' => 'Sector',
                'table_1_count' => $count_sector,
                'table_1_tags' => $sector_tags,
    		    'table_1_add_url'=>'sectorAdd',
				'table_1_edit_url'=>'routes_and_sectors/sector_edit_form/',
				'table_1_get_route'=>'routes_and_sectors/get_sector_data',
                'table_1_del'=>"/routes_and_sectors/sector_delete/",
                
                'table_2' => $routes,
    		    'table_2_name' => 'Route',
                'table_2_count' => $count_route,
                'table_2_tags' => $route_tags,
    		    'table_2_add_url'=>'routeAdd',
    		    'table_2_edit_url'=>'routes_and_sectors/route_edit_form/',
				'table_2_get_route'=>'routes_and_sectors/get_route_data',
                'table_2_del'=>"/routes_and_sectors/route_delete/",
                
                'table_3' => $mtps,
    		    'table_3_name' => 'Multi-pitch',
                'table_3_count' => $count_mtp,
                'table_3_tags' => $mtp_tags,
    		    'table_3_add_url'=>'mtpAdd',
    		    'table_3_edit_url'=>'routes_and_sectors/mtp_edit_form/',
				'table_3_get_route'=>'routes_and_sectors/get_mtp_data',
                'table_3_del'=>"/routes_and_sectors/mtp_delete/",
                
                'table_4' => $mtp_pitchs,
                'table_4_name' => 'pitches', //'Multi-pitch pitches',
                'table_4_count' => $count_mtp_pitchs,
                'table_4_tags' => $mtp_pitch_tags,
                'table_4_add_url'=>'mtpPitchAdd',
                'table_4_edit_url'=>'routes_and_sectors/mtp_pitch_edit_form/',
				'table_4_get_route'=>'routes_and_sectors/get_mtp_pitch_data',
                'table_4_del'=>"/routes_and_sectors/mtp_pitch_delete/",

    		    'page_name' => 'Climbing Secrots And Routes',
    		    'active' => 'routes and sectors',
    		    
    		    // 'num_1'=>1,
    		    // 'num_2'=>1,
    		    // 'num_3'=>1,
    		    
    		    'route_in_sector'=>$route_in_sector
                
    		];
    		return view('user.data_table',$data);
    	}
    	abort(404);
	}


	public function get_sector_data(Request $request)
	{
		return Sector::latest('id')->get();
	}
	public function get_route_data(Request $request)
	{
		return Route::latest('id')->get();
	}
	public function get_mtp_data(Request $request)
	{
		return Mtp::latest('id')->get();
	}
	public function get_mtp_pitch_data(Request $request)
	{
		return Mtp_pitch::latest('id')->get();
	}
	public function get_region_data(Request $request)
	{
		return Article::where('category', '=', 'outdoor')->get();
	}


	public function store()
	{
		$cars = $request -> all();
		
        foreach($cars as $x => $x_value){
            $update = Route::where('id', '=', $x)->update(['number' => $x_value]);
        }
        
        return redirect(route('routes_and_sectors'));
	}


    public function get_routes_for_model(Request $request)
    {
        $routes = Route::where('sector_id',strip_tags($request->sector_id))->orderBy('num')->get();
        return( $routes );
    }

}

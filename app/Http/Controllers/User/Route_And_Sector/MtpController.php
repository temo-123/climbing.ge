<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

class MtpController extends Controller
{
    public function add_mtp(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token'); 
            
            $article = new Mtp();

            $article['sector_id']=$request->sector_id;
            $article['name']=$request->name;
            $article['text']=$request->text; 
            $article['height']=$request->height;
            $article['bolter']=$request->bolter;
            $article['first_ascent']=$request->first_ascent;

            $article -> save();
        }
    	if (view() -> exists('user.components.forms.routes_and_sectors.mtp.mtp_add')) {
    	    $data=[
    			'title' => 'New multy-pitch',
    		];
    		
    		return view('user.components.forms.routes_and_sectors.mtp.mtp_add', $data);
    	}
    	abort(404);
    }

    public function edit_mtp()
    {
        echo "edit mtp";
    }

    public function delete_mtp(Request $request)
    {
        if ($request->isMethod('post')) {
            $sector_id=$request->id;

            $sector = Mtp::where('id',strip_tags($sector_id))->first();

            // dd($sector);

            // delete product file
            // $fileName = $არტიცლე['image'];
            // $destinationPath = 'images/shop_img/';
            // File::delete($destinationPath.$fileName);

            // delete product from db
            $sector ->delete();
        }
    }






    public function add_pitch(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');
            
            $article = new mtp_pitch();

            $article['mtp_id']=$request->mtp_id;
            $article['gread']=$request->gread;
            $article['or_gread']=$request->or_gread; 
            $article['name']=$request->name;
            $article['text']=$request->text;
            $article['height']=$request->height;
            $article['bolts']=$request->bolts;
            $article['bolter']=$request->bolter;
            $article['first_ascent']=$request->first_ascent;

            $article -> save();
        }
    	if (view() -> exists('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_add')) {

            $data=[
    			'title' => 'New route',
            ];
            
    		return view('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_add', $data);
    	}
    	abort(404);
    }

    public function edit_pitch()
    {
        echo "edit pitch";
    }

    public function delete_pitch(Request $request)
    {
        if ($request->isMethod('post')) {
            $sector_id=$request->id;

            $sector = Mtp_pitch::where('id',strip_tags($sector_id))->first();

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

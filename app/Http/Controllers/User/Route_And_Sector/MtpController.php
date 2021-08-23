<?php

namespace App\Http\Controllers\User\Route_And_Sector;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;
use Validate;

class MtpController extends Controller
{
    public function add_mtp(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $this->mtp_validate($request); 
            
            $article = new Mtp();

            $sector_mtp_count = Mtp::where('sector_id',strip_tags($request->sector_id))->count();
            $new_mtp_num = $sector_mtp_count+1;

            $article['sector_id']=$request->sector_id;
            $article['num']=$new_mtp_num;
            $article['name']=$request->name;
            $article['text']=$request->text; 
            $article['height']=$request->height;

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

    public function edit_mtp(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request -> isMethod('post')) {
            $this->mtp_validate($request); 

            $mtp = mtp::find($request->id);

            $mtp->sector_id = $request->sector_id;
            $mtp->name = $request->name;
            $mtp->text = $request->text;
            $mtp->height = $request->height;
            
            $mtp->update();
        }
    }

    public function edit_mtp_form(Request $request)
    {
        if (view()->exists('user.components.forms.routes_and_sectors.mtp.mtp_edit')) {
    		$data = [
    			'name' => 'Edit sector',
                "editing_mtp_id" => $request->id,
    		];

    		return view('user.components.forms.routes_and_sectors.mtp.mtp_edit', $data);
        }
    }
    
    public function get_mtp_editing_data(Request $request)
    {
        $mtp = Mtp::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "mtp" => $mtp,
            ]
        );
    }

    public function delete_mtp(Request $request)
    {
        if ($request->isMethod('post')) {
            $mtp = Mtp::where('id',strip_tags($request->id))->first();

            $mtp_pitch = Mtp_pitch::where('mtp_id',strip_tags($mtp->id))->get();

            // delete mtp from db
            $mtp ->delete();
        }
    }


    public function add_pitch(Request $request)
    {
		$request->user()->authorizeRoles(['manager', 'admin']);
        
        if ($request -> isMethod('post')) {
            $this->mtp_pitch_validate($request);
            
            $article = new mtp_pitch();

            $sector_mtp_pitch_count = Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->count();
            $new_pitch_num = $sector_mtp_pitch_count+1;

            $article['mtp_id']=$request->mtp_id;
            $article['num']=$new_pitch_num;
            $article['grade']=$request->grade;
            $article['or_grade']=$request->or_grade; 
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

    public function edit_pitch(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request -> isMethod('post')) {
            $this->mtp_pitch_validate($request);

            $mtp_pitch = Mtp_pitch::find($request->id);

            $mtp_pitch->mtp_id = $request->mtp_id;
            $mtp_pitch->grade = $request->grade;
            $mtp_pitch->or_grade = $request->or_grade; 
            $mtp_pitch->name = $request->name;
            $mtp_pitch->text = $request->text; 
            $mtp_pitch->height = $request->height;
            $mtp_pitch->bolts = $request->bolts;
            $mtp_pitch->bolter = $request->bolter;
            $mtp_pitch->first_ascent = $request->first_ascent;
            
            $mtp_pitch->update();
        }
    }

    public function edit_pitch_form(Request $request)
    {
        if (view()->exists('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_edit')) {
    		$data = [
    			'name' => 'Edit sector',
                "editing_pitch_id" => $request->id,
    		];

    		return view('user.components.forms.routes_and_sectors.mtp_pitch.mtp_pitch_edit', $data);
        }
    }

    public function get_mtp_pitch_editing_data(Request $request)
    {
        $mtp_pitch = Mtp_pitch::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "mtp_pitch" => $mtp_pitch,
            ]
        );
    }

    public function get_mtp_pitch_for_modal(Request $request)
    {
        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    }

    public function pitchs_sequence(Request $request)
    {
        $pitch_num = 0;
        foreach ($request->pitchs_sequence as $pitch) {
            $pitch_id = $pitch['id'];
            $pitch = Mtp_pitch::where('id',strip_tags($pitch_id))->first();
            $pitch_num++;
            $pitch['num'] = $pitch_num;
            $pitch->update();
        }
    }

    public function delete_pitch(Request $request)
    {
        if ($request->isMethod('post')) {
            $sector_id=$request->id;

            $sector = Mtp_pitch::where('id',strip_tags($sector_id))->first();

            // delete product from db
            $sector ->delete();
        }
    }

    public function mtp_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'sector_id' => 'required',
        ]);
    }
    public function mtp_pitch_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'grade' => 'required',
            'mtp_id' => 'required',
        ]);
    }
}

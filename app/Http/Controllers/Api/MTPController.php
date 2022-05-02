<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

class MTPController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return MTP::get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mtp = Mtp::where('id',strip_tags($id))->first();
        $mtp_pitch_count = Mtp_pitch::where('mtp_id',strip_tags($mtp->id))->count();
        if ($mtp_pitch_count > 0) {
            $mtp_pitch = Mtp_pitch::where('mtp_id',strip_tags($mtp->id))->get();
            $mtp_pitch ->delete();
        }
        $mtp ->delete();
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
    public function mtp_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'sector_id' => 'required',
        ]);
    }
}

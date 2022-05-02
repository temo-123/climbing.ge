<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Sector;
use App\Models\Route;
use App\Models\Mtp;
use App\Models\Mtp_pitch;

class MTPPitchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Mtp_pitch::get();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sector = Mtp_pitch::where('id',strip_tags($id))->first();
        $sector ->delete();
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
    public function mtp_pitch_validate($request)
    {
        $request->validate([
            'name' => 'required|max:190',
            'grade' => 'required',
            'mtp_id' => 'required',
        ]);
    }
}

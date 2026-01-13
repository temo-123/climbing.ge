<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

class MTPPitchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Mtp_pitch::latest('id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_pitchs(Request $request)
    {

        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
        // $mtp = Mtp_pitch::where('id', $request->mtp_id)->first();
        // dd($mtp);
        // return $mtp->pitchs;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     //
    // }


    // public function pitch_sequence(Request $request)
    // {
    //     if($request->pitch_sequence){
    //         $pitch_num = 0;
    //         foreach ($request->pitch_sequence as $pitch) {
    //             $pitch_id = $pitch['id'];
    //             $pitch = Mtp_pitch::where('id',strip_tags($pitch_id))->first();
    //             $pitch_num++;
    //             $pitch['num'] = $pitch_num;
    //             $pitch->update();
    //         }
    //     }
    // }

    public function get_mtp_pitchs(Request $request)
    {
        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
        // return( $mtp_pitchs );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function mtp_pitch_edit(Request $request)
    // {
    //     $pitch_validate = $this->pitch_validate($request->data);
    //     if ($pitch_validate != null) { 
    //         return response()->json([
    //             $pitch_validate
    //         ], 422);
    //     }
    //     else{
    //         if ($request -> isMethod('post')) {
    //             $article = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();

    //             $article['mtp_id']=$request->data['mtp_id'];
    //             $article['category']=$request->data['category'];
    //             $article['grade']=$request->data['grade'];
    //             $article['or_grade']=$request->data['or_grade'] ;
    //             $article['name']=$request->data['name'];
    //             $article['text']=$request->data['text'];
    //             $article['height']=$request->data['height'];
    //             $article['bolts']=$request->data['bolts'];

    //             $article['author'] = $request->data["author"];
    //             $article['creation_data'] = $request->data["creation_data"];
    //             $article['first_ascent'] = $request->data["first_ascent"];

    //             $article -> save();
    //         }
    //     }
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function mtp_pitch_add(Request $request)
    // {
    //     $pitch_validate = $this->pitch_validate($request->data);
    //     if ($pitch_validate != null) { 
    //         return response()->json([
    //             $pitch_validate
    //         ], 422);
    //     }
    //     else{
    //         if ($request -> isMethod('post')) {
    //             $new_pitch = new mtp_pitch();

    //             $sector_mtp_pitch_count = Mtp_pitch::where('mtp_id',strip_tags($request->data['mtp_id']))->count();
    //             if($sector_mtp_pitch_count == 0){
    //                 $new_pitch_num = 1;
    //             }
    //             else{
    //                 $new_pitch_num = $sector_mtp_pitch_count+1;
    //             }

    //             $new_pitch['mtp_id']=$request->data['mtp_id'];
    //             $new_pitch['num']=$new_pitch_num;
    //             $new_pitch['category']=$request->data['category'];
    //             $new_pitch['grade']=$request->data['grade'];
    //             $new_pitch['or_grade']=$request->data['or_grade'] ;
    //             $new_pitch['name']=$request->data['name'];
    //             $new_pitch['text']=$request->data['text'];
    //             $new_pitch['height']=$request->data['height'];
    //             $new_pitch['bolts']=$request->data['bolts'];

    //             $new_pitch['author'] = $request->data["author"];
    //             $new_pitch['creation_data'] = $request->data["creation_data"];
    //             $new_pitch['first_ascent'] = $request->data["first_ascent"];

    //             $new_pitch -> save();
    //         }
    //     }
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function del_pitch(Request $request)
    // {
    //     $sector = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();
    //     $sector ->delete();
    // }



    // public function get_editin_pitch(Request $request)
    // {
    //     return Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();
    // }


    // public function get_mtp_pitch_for_modal(Request $request)
    // {
    //     return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    // }

    // public function pitch_validate($request)
    // {
    //     $validator = Validator::make($request, [
    //         'name' => 'required|max:190',
    //         'grade' => 'required',
    //         'mtp_id' => 'required',
    //     ]);
        
    //     if ($validator->fails()) {
    //         return response()->json($validator->errors(), 422);
    //     }
    // }
}

<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use Validator;
use App\Services\PermissionService;

class MTPController extends Controller
{
    public function mtp_add(Request $request)
    {
        $auth = PermissionService::authorize('mtp', 'add');
        // if ($auth) return $auth;
        
        // dd($request->data);
        $mtp_validate = $this->mtp_validate($request->data);
        if ($mtp_validate != null) { 
            return response()->json([
                $mtp_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                
                $new_mtp = new Mtp();

                $sector_mtp_pitch_count = Mtp::where('sector_id',strip_tags($request->data['sector_id']))->count();
                $new_mtp->num = $sector_mtp_pitch_count+1;

                $new_mtp->sector_id = $request->data['sector_id'];
                $new_mtp->name = $request->data['name'];
                $new_mtp->text = $request->data['text'];
                $new_mtp->height = $request->data['height'];
                $new_mtp->first_ascent = $request->data['first_ascent'];
                $new_mtp->author = $request->data['author'];
                
                $new_mtp->save();
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function mtp_edit(Request $request, $id)
    {
        $auth = PermissionService::authorize('mtp', 'edit');
        // if ($auth) return $auth;
        
        $mtp_validate = $this->mtp_validate($request->data);
        if ($mtp_validate != null) { 
            return response()->json([
                $mtp_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                $edit_mtp = Mtp::where('id',strip_tags($request->mtp_id))->first();

                $edit_mtp->sector_id = $request->data['sector_id'];
                $edit_mtp->name = $request->data['name'];
                $edit_mtp->text = $request->data['text'];
                $edit_mtp->height = $request->data['height'];
                $edit_mtp->first_ascent = $request->data['first_ascent'];
                $edit_mtp->author = $request->data['author'];
                
                $edit_mtp->save();
            }
        }
    }

    public function get_editing_mtp(Request $request, $id)
    {
        $auth = PermissionService::authorize('mtp', 'edit');
        // if ($auth) return $auth;
        
        return Mtp::where('id',strip_tags($request->mtp_id))->first();
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_mtp(Request $request)
    {
        $auth = PermissionService::authorize('mtp', 'del');
        if ($auth) return $auth;
        
        $mtp = Mtp::where('id',strip_tags($request->mtp_id))->first();
        $mtp_pitchs_count = Mtp_pitch::where('mtp_id',strip_tags($mtp->id))->count();
        if ($mtp_pitchs_count > 0) {
            $mtp_pitchs = Mtp_pitch::where('mtp_id',strip_tags($mtp->id))->get();
            foreach ($mtp_pitchs as $pitch) {
                $pitch ->delete();
            }
        }
        $mtp ->delete();
    }

    public function get_mtp_editing_data(Request $request)
    {
        $auth = PermissionService::authorize('mtp', 'edit');
        // if ($auth) return $auth;
        
        $mtp = Mtp::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "mtp" => $mtp,
            ]
        );
    }
    public function mtp_validate($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required | max:190',
            'sector_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
    }
}

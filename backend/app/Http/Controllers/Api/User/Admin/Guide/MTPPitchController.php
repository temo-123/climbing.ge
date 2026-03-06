<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use App\Services\PermissionService;

class MTPPitchController extends Controller
{
    public function get_mtp_pitchs_for_model(Request $request)
    {
        // $auth = PermissionService::authorize('mtp_pitch', 'view');
        // if ($auth) return $auth;
        
        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    }

    public function pitch_sequence(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;
        
        if($request->pitch_sequence){
            $pitch_num = 0;
            foreach ($request->pitch_sequence as $pitch) {
                $pitch_id = $pitch['id'];
                $pitch = Mtp_pitch::where('id',strip_tags($pitch_id))->first();
                $pitch_num++;
                $pitch['num'] = $pitch_num;
                $pitch->update();
            }
        }
    }

    public function mtp_pitch_edit(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;
        
        $pitch_validate = $this->pitch_validate($request->data);
        if ($pitch_validate != null) { 
            return response()->json([
                $pitch_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                $article = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();

                $article['mtp_id']=$request->data['mtp_id'];
                $article['category']=$request->data['category'];
                $article['grade']=$request->data['grade'];
                $article['or_grade']=$request->data['or_grade'] ;
                $article['name']=$request->data['name'];
                $article['text']=$request->data['text'];
                $article['height']=$request->data['height'];
                $article['bolts']=$request->data['bolts'];

                $article['author'] = $request->data["author"];
                $article['creation_data'] = $request->data["creation_data"];
                $article['first_ascent'] = $request->data["first_ascent"];

                $article -> save();
            }
        }
    }

    public function mtp_pitch_add(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'add');
        if ($auth) return $auth;
        
        $pitch_validate = $this->pitch_validate($request->data);
        if ($pitch_validate != null) { 
            return response()->json([
                $pitch_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                $new_pitch = new mtp_pitch();

                $sector_mtp_pitch_count = Mtp_pitch::where('mtp_id',strip_tags($request->data['mtp_id']))->count();
                if($sector_mtp_pitch_count == 0){
                    $new_pitch_num = 1;
                }
                else{
                    $new_pitch_num = $sector_mtp_pitch_count+1;
                }

                $new_pitch['mtp_id']=$request->data['mtp_id'];
                $new_pitch['num']=$new_pitch_num;
                $new_pitch['category']=$request->data['category'];
                $new_pitch['grade']=$request->data['grade'];
                $new_pitch['or_grade']=$request->data['or_grade'] ;
                $new_pitch['name']=$request->data['name'];
                $new_pitch['text']=$request->data['text'];
                $new_pitch['height']=$request->data['height'];
                $new_pitch['bolts']=$request->data['bolts'];

                $new_pitch['author'] = $request->data["author"];
                $new_pitch['creation_data'] = $request->data["creation_data"];
                $new_pitch['first_ascent'] = $request->data["first_ascent"];

                $new_pitch -> save();
            }
        }
    }

    public function del_pitch(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'del');
        if ($auth) return $auth;
        
        $sector = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();
        $sector ->delete();
    }

    public function get_editin_pitch(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;
        
        return Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();
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
    public function pitch_validate($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required|max:190',
            'grade' => 'required',
            'mtp_id' => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
}

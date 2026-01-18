<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\General_info;
use App\Services\GeneralInfoService;
use App\Services\PermissionService;
use Illuminate\Support\Facades\Log;

class GeneralInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function get_all_general_info()
    // {
    //     return General_info::get();
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_general_info(Request $request)
    {
        $auth = PermissionService::authorize('general_info', 'add');
        if ($auth) return $auth;
        
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $new_general_info = new General_info;

            $new_general_info['title'] = $request->data['title'];

            $new_general_info['text_us'] = $request->data['us_text'];
            $new_general_info['text_ka'] = $request->data['ka_text'];
            // $new_general_info['text_ru'] = $request->data['ru_text'];

            $new_general_info -> save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_editing_general_info($id)
    {
        $auth = PermissionService::authorize('general_info', 'view');
        if ($auth) return $auth;
        return General_info::where("id", "=", $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_general_info(Request $request, $id)
    {
        $auth = PermissionService::authorize('general_info', 'edit');
        if ($auth) return $auth;
        
        $validate = $this->validation($request);

        if ($validate != null) {
            return($validate);
        }
        else{
            $editing_general_info = General_info::where("id", "=", $id)->first();
    
            $editing_general_info['title'] = $request->data['title'];
    
            $editing_general_info['text_us'] = $request->data['us_text'];
            $editing_general_info['text_ka'] = $request->data['ka_text'];
            // $editing_general_info['text_ru'] = $request->data['ru_text'];
    
            $editing_general_info -> save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_general_info($id)
    {
        $auth = PermissionService::authorize('general_info', 'del');
        if ($auth) return $auth;
        
        try {
            $success = GeneralInfoService::deleteGeneralInfo($id);
            
            if ($success) {
                return response()->json([
                    'message' => 'General info deleted successfully',
                    'status' => 'success'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'General info not found',
                    'status' => 'error'
                ], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting general info', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'message' => 'Failed to delete general info',
                'error' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    public function validation($request)
    {
        $validator = validator($data = $request->data, [
            'title' => 'required',
            'us_text' => 'required',
            // 'ru_text' => 'required',
            'ka_text' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }
    }
}

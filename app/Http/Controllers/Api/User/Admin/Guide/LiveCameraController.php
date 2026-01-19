<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\LiveCamera;
use App\Services\PermissionService;

class LiveCameraController extends Controller
{
    // function get_live_cameras(Request $request) {
    //     return LiveCamera::all();
    // }

    function add_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'add');
        if ($auth) return $auth;
        
        $liveCamera = LiveCamera::create($request->all('data')['data']);
        return response()->json($liveCamera, 201);
    }

    function edit_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;
        
        $liveCamera = LiveCamera::find($request->id);
        if (!$liveCamera) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $liveCamera->update($request->all('data')['data']);
        return response()->json($liveCamera);
    }

    function get_editing_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;
        
        return LiveCamera::where('id', '=', $request->id)->first();
    }

    // function get_activ_live_camera(Request $request) {
    //     return LiveCamera::where('id', '=', $request->id)->first();
    // }

    function del_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'del');
        if ($auth) return $auth;
        
        // dd($request->id);
        $deleted = LiveCamera::where('id', $request->id)->delete();
        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }
}

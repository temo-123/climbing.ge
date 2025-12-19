<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\LiveCamera;

class LiveCameraController extends Controller
{
    function get_activ_live_camera(Request $request) {
        return LiveCamera::where('id', '=', $request->id)->first();
    }

    function get_live_cameras(Request $request) {
        return LiveCamera::all();
    }
}

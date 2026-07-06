<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Guide\General_info;
use App\Services\ActionTrackingService;

class GeneralInfoController extends Controller
{
    public function get_all_general_infos()
    {
        return General_info::get();
    }

    public function show($id)
    {
        return General_info::where("id", "=", $id)->first();
    }

    public function track_action(Request $request)
    {
        $validator = validator($request->all(), [
            'block_id'    => 'required|integer',
            'action_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $success = ActionTrackingService::saveAction(
            $request->block_id,
            $request->action_type ?: 'show_more_click'
        );

        // Failure here means the click counter wasn't recorded (e.g. Redis is
        // unreachable) — already logged in ActionTrackingService. It's not
        // worth surfacing as a hard error to real visitors on every guide page.
        return response()->json([
            'status' => $success ? 'success' : 'error',
        ]);
    }
}

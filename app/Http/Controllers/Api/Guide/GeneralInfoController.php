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

        return response()->json([
            'status' => $success ? 'success' : 'error',
        ], $success ? 200 : 500);
    }
}

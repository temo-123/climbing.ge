<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\General_info;
use App\Services\GeneralInfoService;
use App\Services\PermissionService;
use App\Services\ActionTrackingService;
use Illuminate\Support\Facades\Log;

class GeneralInfoController extends Controller
{
    /**
     * Track action for a block (show more click)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function track_action(Request $request)
    {
        $validator = validator($request->all(), [
            'block_id' => 'required|integer',
            'action_type' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $blockId = $request->block_id;
        $actionType = $request->action_type ?: 'show_more_click';

        $success = ActionTrackingService::saveAction($blockId, $actionType);

        if ($success) {
            return response()->json([
                'message' => 'Action tracked successfully',
                'status' => 'success'
            ], 200);
        }

        return response()->json([
            'message' => 'Failed to track action',
            'status' => 'error'
        ], 500);
    }

    /**
     * Get action statistics for a block
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_action_stats(Request $request)
    {
        $validator = validator($request->all(), [
            'block_id' => 'required|integer',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'action_type' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $blockId = $request->block_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $actionType = $request->action_type ?: 'show_more_click';

        if ($startDate && $endDate) {
            $counts = ActionTrackingService::getActionCountsByDateRange($startDate, $endDate, $blockId, $actionType);
        } else {
            $counts = ActionTrackingService::getActionCount($blockId, null, $actionType);
        }

        return response()->json([
            'data' => $counts,
            'status' => 'success'
        ], 200);
    }
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

            $new_general_info['text_us'] = $request->data['text_us'];
            $new_general_info['text_ka'] = $request->data['text_ka'];

            $new_general_info['is_show'] = $request->data['is_show'];

            $new_general_info -> save();
        }
    }

    public function get_editing_general_info($id)
    {
        $auth = PermissionService::authorize('general_info', 'edit');
        if ($auth) return $auth;
        return General_info::where("id", "=", $id)->first();
    }

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
    
            $editing_general_info['text_us'] = $request->data['text_us'];
            $editing_general_info['text_ka'] = $request->data['text_ka'];

            $editing_general_info['is_show'] = $request->data['is_show'];
    
            $editing_general_info -> save();
        }
    }

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
            'text_us' => 'required',
            'text_ka' => 'required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }
    }
}

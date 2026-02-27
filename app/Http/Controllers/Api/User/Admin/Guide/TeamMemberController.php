<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Services\PermissionService;

class TeamMemberController extends Controller
{

    public function edit_member_status(Request $request, $id)
    {
        $auth = PermissionService::authorize('team', 'edit');
        if ($auth) return $auth;
        
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->is_team_member = $request->input('is_team_member', false);
        $user->member_status = $request->input('member_status', '');
        $user->save();
        return response()->json(['success' => true]);
    }

}

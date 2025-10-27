<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class TeamMemberController extends Controller
{
    public function get_member_status(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json([
            'is_team_member' => $user->is_team_member,
            'member_status' => $user->member_status,
        ]);
    }

    public function edit_member_status(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->is_team_member = $request->input('is_team_member', false);
        $user->member_status = $request->input('member_status', '');
        $user->save();
        return response()->json(['success' => true]);
    }

    public function get_team_members()
    {
        return User::where('is_team_member', '!=', null)->get();
    }

    public function get_team_member_data(Request $request)
    {
        return User::where('id', '=', $request->id)->get();
    }
}

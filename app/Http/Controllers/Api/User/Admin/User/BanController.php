<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\User\Role;
use App\Services\PermissionService;
use Illuminate\Http\Request;

class BanController extends Controller
{
    private function getBanRole()
    {
        return Role::where('slug', 'ban')->first();
    }

    public function get_ban_status($user_id)
    {
        $auth = PermissionService::authorize('user', 'create_ban');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);
        $banRole = $this->getBanRole();

        return response()->json([
            'is_banned' => $user->isBanned(),
            'ban_role_exists' => (bool) $banRole,
        ]);
    }

    public function create_ban(Request $request, $user_id)
    {
        $auth = PermissionService::authorize('user', 'create_ban');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);

        if (auth()->id() === $user->id) {
            return response()->json(['message' => 'You cannot ban yourself.'], 403);
        }

        $banRole = $this->getBanRole();
        if (!$banRole) {
            return response()->json(['message' => 'Ban role not found. Please create a role with slug "ban" first.'], 404);
        }

        if ($user->isBanned()) {
            return response()->json(['message' => 'User is already banned.'], 422);
        }

        $user->role()->attach($banRole->id);
        $user->tokens()->delete();

        return response()->json(['message' => 'User has been banned.']);
    }

    public function remove_ban($user_id)
    {
        $auth = PermissionService::authorize('user', 'create_ban');
        if ($auth) return $auth;

        $user = User::findOrFail($user_id);

        $banRole = $this->getBanRole();
        if (!$banRole) {
            return response()->json(['message' => 'Ban role not found.'], 404);
        }

        $user->role()->detach($banRole->id);

        return response()->json(['message' => 'User has been unbanned.']);
    }
}

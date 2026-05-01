<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Role;
use App\Models\User\Permission;

use App\Services\PermissionService;

class PermissionsController extends Controller
{
    public function get_parmisions_for_role(Request $request)
    {
        $auth = PermissionService::authorize('permission', 'edit');
        if ($auth) return $auth;
        $role = Role::where('id', '=', $request->role_id)->first();
        $all_permissions = Permission::get();
        return $all_permissions;
    }

    public function store(Request $request)
    {
        $auth = PermissionService::authorize('permission', 'edit');
        if ($auth) return $auth;
        $request->validate([
            'subject' => 'required|string|max:100',
            'action'  => 'required|string|max:100',
        ]);

        $exists = Permission::where('subject', $request->subject)
            ->where('action', $request->action)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Permission already exists'], 422);
        }

        $permission = new Permission;
        $permission->subject = $request->subject;
        $permission->action  = $request->action;
        $permission->save();

        return response()->json($permission, 201);
    }

    public function destroy($id)
    {
        $auth = PermissionService::authorize('permission', 'edit');
        if ($auth) return $auth;
        Permission::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}

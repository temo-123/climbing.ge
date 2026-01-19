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
        // $auth = PermissionService::authorize('permission', 'view');
        // if ($auth) return $auth;
        
        $role = Role::where('id', '=', $request->role_id)->first();
        $role_permissions = $role->permissions;

        $all_permissions = Permission::get();

        return $all_permissions;
    }
}

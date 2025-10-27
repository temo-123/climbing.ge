<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Role;
use App\Models\User\Permission;

class PermissionsController extends Controller
{
    public function get_parmisions_for_role(Request $request)
    {
        $role = Role::where('id', '=', $request->role_id)->first();
        $role_permissions = $role->permissions;

        $all_permissions = Permission::get();

        // $other_permissions = array();
        // foreach($all_permissions as $permission){
        //     foreach ($role_permissions as $role_permission) {
        //         if($role_permission->id != $permission->id){
        //             array_push($other_permissions, $permission);
        //         }
        //     }

        // }

        // dd($other_permissions);

        // return $other_permissions;

        return $all_permissions;
    }
}

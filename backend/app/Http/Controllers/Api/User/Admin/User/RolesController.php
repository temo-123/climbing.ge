<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Role;
use App\Models\User\Role_permission;
use App\Models\User\Permission;
use App\Models\User;
use App\Models\User\User_permission;
use App\Models\User\User_role;

use App\Services\PermissionService;

use App\Notifications\permission\ChangePermissionNotification;
use Notification;

class RolesController extends Controller
{
    public function index()
    {
        // $auth = PermissionService::authorize('role', 'view');
        // if ($auth) return $auth;
        return Role::latest('id')->get();
    }

    public function get_parmisions_list()
    {
        // $auth = PermissionService::authorize('permission', 'view');
        // if ($auth) return $auth;
        return Permission::latest('id')->get();
    }

    public function get_user_role(Type $var = null)
    {
        # code...
    }

    public function get_user_permissions(Request $request)
    {
        $auth = PermissionService::authorize('user', 'edit');
        // if ($auth) return $auth;
        $user = User::where("id", "=", $request->user_id)->first();
        $user_role = array();
        if($user->role->count() > 0){
            $role = $user->role;
            $user_role = $role[0];
        }
        else{
            $user_role = [];
        }
        $user_permissions = $user->permissions;

        $data = [
            'user' => $user,
            'role' => $user_role,
            'permissions' => $user_permissions
        ];
        // dd($data);
        return $data;
    }

    public function del_user_pemisino(Request $request)
    {
        $auth = PermissionService::authorize('user', 'edit');
        // if ($auth) return $auth;
        $permission = User_permission::where("permission_id", "=", $request->permission_id)->where("user_id", "=", $request->user_id)->first();
        $permission -> delete();
    }

    public function edit_permissions_and_role(Request $request)
    {   
        $auth = PermissionService::authorize('user', 'edit');
        // if ($auth) return $auth;
        $message = '';
        if ($request->role) {
            $user_role = User_role::where("user_id", "=", $request->user_id)->first();
            if($user_role){
                $user_role['role_id'] = $request->role;
                $user_role->save();
            }
            else{
                $per = new User_role;
                $per['user_id'] = $request->user_id;
                $per['role_id'] = $request->role;
                $per->save();
            }
            
            $message = 'You have new role - "' . Role::where("id", "=", $request->role)->first()->name . '".';
        }

        if ($request->new_permissions) {
            foreach ($request->new_permissions as $permission) {
                $per = new User_permission;
                $per['user_id'] = $request->user_id;
                $per['permission_id'] = $permission['permission_id'];
                $per->save();
            }
            $message = "You have new permission/s. A new feature is available for you.";
        }

        $info = [
            "message" => $message
        ];
        
        $user = user::where("id", "=", $request->user_id)->first();
        Notification::route('mail', $user['email'])->notify(new ChangePermissionNotification($info));
    }

    public function create_role(Request $request)
    {
        $auth = PermissionService::authorize('role', 'add');
        // if ($auth) return $auth;
        $per = new Role;
        $per['name'] = $request->role_data['name'];
        $per['description'] = $request->role_data['description'];
        $per->save();

        $this->save_role_permissions($request->new_permissions, $per['id']);
    }

    public function get_editing_role(Request $request)
    {
        $auth = PermissionService::authorize('role', 'edit');
        // if ($auth) return $auth;
        return Role::where("id", "=", $request->role_id)->first();
    }

    public function get_editing_role_permissions(Request $request)
    {
        $auth = PermissionService::authorize('role', 'edit');
        // if ($auth) return $auth;
        $role = Role::where("id", "=", $request->role_id)->first();
        return $role->permissions;
    }

    public function edit_role(Request $request)
    {
        $auth = PermissionService::authorize('role', 'edit');
        // if ($auth) return $auth;
        $editing_role = Role::where("id", "=", $request->role_id)->first();
        $saiving_action = 0;

        if($editing_role -> name != $request->role['name']){
            $saiving_action++;
            $editing_role -> name = $request->role['name'];
        }
        if($editing_role -> description != $request->role['description']){
            $saiving_action++;
            $editing_role -> description = $request->role['description'];
        }

        if($saiving_action > 0){
            $editing_role->save();
        }

        $this->save_role_permissions($request->new_permissions, $editing_role->id);
    }

    public function save_role_permissions($permission_array, $role_id)
    {
        foreach ($permission_array as $permission) {
            $per = new Role_permission;
            $per['role_id'] = $role_id;
            $per['permission_id'] = $permission['permission_id'];
            $per->save();
        }
    }

    public function del_role(Request $request)
    {
        $auth = PermissionService::authorize('role', 'del');
        // if ($auth) return $auth;
        $role = Role::where("id", "=", $request->role_id)->first();

        $role_permision = Role_permission::where("role_id", "=", $role->id)->get();
        foreach ($role_permision as $r_p) {
            $r_p -> delete();
        }

        $role -> delete();
    }

    
    public function del_role_permission(Request $request)
    {
        $auth = PermissionService::authorize('role', 'del');
        $role_permision = Role_permission::where("role_id", "=", $request->role_id)->where("permission_id", "=", $request->permission_id)->first();

        $role_permision -> delete();
    }
}

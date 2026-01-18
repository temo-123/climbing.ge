<?php

namespace App\Http\Controllers\Api\User\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Mail;
use Hash;

use App\Models\User;
use App\Models\User\Role;
use App\Models\User\Permission;
use App\Models\User\user_notification;

use App\Models\User\User_role;
use App\Models\User\User_permission;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

class UsersController extends Controller
{
    public function get_all_users()
    {
        $auth = PermissionService::authorize('user', 'view');
        // if ($auth) return $auth;
        return User::latest('id')->get();
    }

    public function get_auth_user_data() {
        return auth()->user()->first();
    }

    public function get_user_data(Request $request) {
        $auth = PermissionService::authorize('user', 'view');
        // if ($auth) return $auth;
        return user::where('id', '=', $request->user_id)->first();
    }

    public function get_worker_users() {
        $auth = PermissionService::authorize('user', 'view');
        // if ($auth) return $auth;
        $all_users = User::get();
        $prmishened_users = [];

        foreach ($all_users as $user) {
            $user_perms = $user->permissions;
            // dd($user_perms);
            foreach ($user_perms as $user_perm) {
                if ($user_perm['subject'] == 'worker') {
                    array_push($prmishened_users, $user);
                }
            }
        }

        return $prmishened_users;
    }

    public function get_team_members() {
        $auth = PermissionService::authorize('user', 'view');
        // if ($auth) return $auth;
        return User::where('is_team_member', '!=', null)->get();
    }

    public function create_user_by_admin(Request $request)
    {
        $auth = PermissionService::authorize('user', 'add');
        // if ($auth) return $auth;
        $validator = Validator::make($request['data'], [
            'name' => 'required',
            'surname' => 'required',

            'email' => 'required|unique:users,email',

            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }
        else{
            $new_user =  new User();

            $new_user['name'] = $request['data']['name'];
            $new_user['surname'] = $request['data']['surname'];
            $new_user['country'] = $request['data']['country'];
            $new_user['city'] = $request['data']['city'];
            $new_user['phone_number'] = $request['data']['phone_number'];
            $new_user['email'] = $request['data']['email'];
            $new_user['password'] = bcrypt($request['data']['password']);

            $new_user -> save();

            $this->create_user_notifications($new_user->id);
            $this->create_user_permissions($new_user->id);
        }
    }

    public function update_password(Request $request)
    {
        #Match The Old Password
        if(!Hash::check($request['data']['old_pass'], auth()->user()->password)){
            return "Old Password Doesn't match!";
        }

        #Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request['data']['new_pass'])
        ]);

        return "Password changed successfully!";
    }


    public function get_auth_user_permissions(Request $request)
    {
        if (Auth::user()) {
            $user = Auth::user();

            $user_all_permissions = [];

            $user_permissons = $user->permissions;
            if ($user_permissons) {
                foreach ($user_permissons as $permisson) {
                    array_push($user_all_permissions, 
                        ['subject' => $permisson->subject,  'action' => $permisson->action]
                    );
                }
            }

            $user_role = $user->role->first();

            if ($user_role) {
                $user_role_perissions = $user_role->permissions;
                if ($user_role_perissions) {
                    foreach ($user_role_perissions as $user_role_perission) {
                        array_push($user_all_permissions, 
                            ['subject' => $user_role_perission->subject,  'action' => $user_role_perission->action]
                        );
                    }
                }
            }

            return $user_all_permissions;
        }
        else{
            // return 'plees login';
            return response()->json('Plees login', 401);
        }
    }
    public function user_image_update(Request $request)
    {
        if (Auth::user()) {
            $user = user::where('id', '=', $request->user_id)->first();
            if($user->image != null){
                if($request->hasFile('image')){
                    $file_new_name = ImageControllService::image_update('images/user_profil_img/', $user, $request, 'image', 'image');
                    $user['image'] = $file_new_name;
                }
        
                $user -> save();
            }
            else{
                if($request->hasFile('image')){
                    $file_new_name = ImageControllService::image_upload('images/user_profil_img/', $request, 'image');
                    $user['image'] = $file_new_name;
                }
        
                $user -> save();
            }
        }
        else{
            return 'Plees login';
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function create_user_permissions(int $user_id)
    {
        if(User_role::where('user_id', '=', $user_id)->count() == 0){
            $new_permission_item =  new User_role();

            $new_permission_item['user_id'] = $user_id;
            $new_permission_item['role_id'] = Role::where('slug', '=', 'user')->first()->id; // ID 1 in role tab is a user

            $new_permission_item -> save();
        }
    }

    public function create_user_notifications($user_id)
    {
        $new_notification =  new user_notification();

        $new_notification['user_id'] = $user_id;

        $new_notification['add_new_gym'] = 1;
        $new_notification['news'] = 1;
        $new_notification['add_new_ice_spot'] = 1;
        $new_notification['add_new_outdoor_spot'] = 1;
        $new_notification['add_new_product'] = 1;
        $new_notification['add_new_sector'] = 1;
        $new_notification['add_new_service'] = 1;
        $new_notification['add_new_techtip'] = 1;
        $new_notification['favorite_film'] = 1;
        $new_notification['favorite_outdoor'] = 1;
        $new_notification['favorite_product'] = 1;
        $new_notification['interested_event'] = 1;

        $new_notification -> save();
    }

    public function del_user(Request $request)
    {
        $auth = PermissionService::authorize('user', 'del');
        // if ($auth) return $auth;
        $deliting_user = User::where('id',strip_tags($request->user_id))->first();

        if (Auth::user()->id != $deliting_user->id) {
            if(User_role::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_roles = User_role::where('user_id', '=', $deliting_user->id)->first();
                $user_roles -> delete();
            }
    
            if(user_notification::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_notifications = user_notification::where('user_id', '=', $deliting_user->id)->first();
                $user_notifications -> delete();
            }
    
            if(User_permission::where('user_id', '=', $deliting_user->id)->count() > 0){
                $user_parmissions = User_permission::where('user_id', '=', $deliting_user->id)->get();
    
                foreach ($user_parmissions as $user_parmission) {
                    $user_parmission -> delete();
                }
            }

            if ($deliting_user->image) {
                ImageControllService::image_delete('images/user_profil_img/'.$deliting_user->image.'_img/', $deliting_user, 'image');
            }

            $deliting_user ->delete();

            return("User deleted socsesful");
        }
        else {
            return("You don`t can delete yourself! :)");
        }
    }
}

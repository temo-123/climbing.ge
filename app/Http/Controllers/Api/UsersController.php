<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Mail;

use App\User;
use App\Models\Following_users;
use App\Models\Role;
use App\Models\Permission;
use App\Models\user_notification;
use App\Models\User_role;
use App\Models\User_permission;

use App\Services\ImageControllService;

use App\Notifications\FollowingNotification;
// use App\Mail\Message;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(Auth::user());
        return User::latest('id')->get();
    }

    public function get_following_users_list()
    {
        return following_users::latest('id')->get();
    }

    public function get_followers_list()
    {
        return following_users::latest('id')->get();
    }

    public function follow(Request $request)
    {
        if ($request -> isMethod('post')) {
            if(Following_users::where('email','=',$request->email)->count()){
                return("You are already subscribed");
            }
            else{
                if (auth()->user() && $request->email == auth()->user()->email) {
                    $follow = new Following_users();
                    $follow['email']=$request->email;
                    $follow['service']=$request->service_id;
                    $follow['user_id']=auth()->user();
                    $follow -> save();
                }
                else {
                    $follow = new Following_users();
                    $follow['email']=$request->email;
                    $follow['service']=$request->service_id;
                    $follow -> save();
                }

                $EmailArray = array(
                    'email' => "rrrr@ggg.ggg",
                    'message' => 'test',
                    'from_site' => $request->service_id,
                );

                Mail::to($request->email)->send(new FollowingNotification($EmailArray));

                return("Tenk you for following! :) Plis check your emeil!");
            }
        }
    }

    public function get_auth_user_permissions(Request $request)
    {
        // dd(Auth::user());
        if (Auth::user()) {
            $auth_user = Auth::user();

            $test_user = User::where("id", "=", 1)->first();
            dd( $test_user->perm);

            // $user = User::first();
            // $user_role = $user->roles;
            // $user_permissions = $user->permissions;

            // dd($user_role, $user_permissions);

            // $user_role = $auth_user -> roles;
            // dd($user_role);

            // $user_permissions = $auth_user -> permissions;
            // dd($user_permissions);
            
            // $roles = Role::where("id", "=", $user_role->role_id)->get();

            // $permissons = Permission::where("id", "=", $user_permissons->permission_id)->get();

            $user_all_permissions = [];

            if ($permissons) {
                foreach ($permissons as $permisson) {
                    array_push($user_all_permissions, 
                        $permisson
                    );
                }
            }

            if ($roles) {
                foreach ($roles as $role) {
                    array_push($user_all_permissions, 
                        $role
                    );
                }
            }

            dd($user_all_permissions);
        }
        else{
            return 'plees login';
        }
    }

    public function user_info_update(Request $request)
    {
        if (Auth::user()) {
            if(Auth::user()->id == $request->user_id){
                $editing_item = User::where('id', '=', Auth::user()->id)->first();

                $editing_item['name'] = $request->data['name'];
                $editing_item['surname'] = $request->data['surname'];
                $editing_item['country'] = $request->data['country'];
                $editing_item['city'] = $request->data['city'];
                $editing_item['phone_number'] = $request->data['phone_number'];
                $editing_item['email'] = $request->data['email'];

                $editing_item -> update();
            }
            else{
                return 'Error';
            }
        }
        else{
            return 'Plees login';
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

    public function get_user_notification_data(Request $request)
    {
        if (Auth::user()) {
            return user_notification::where('user_id', '=', Auth::user()->id)->first();
        }
        else{
            return 'Plees login';
        }
    }

    public function update_user_notification_data(Request $request)
    {
        if (Auth::user()) {
            $editing_item =  user_notification::where('id', '=', $request->notification_id)->first();

            $editing_item['add_new_gym'] = $request->data['add_new_gym'];
            $editing_item['add_new_ice_spot'] = $request->data['add_new_ice_spot'];
            $editing_item['add_new_outdoor_spot'] = $request->data['add_new_outdoor_spot'];
            $editing_item['add_new_product'] = $request->data['add_new_product'];
            $editing_item['add_new_sector'] = $request->data['add_new_sector'];
            $editing_item['add_new_service'] = $request->data['add_new_service'];
            $editing_item['add_new_techtip'] = $request->data['add_new_techtip'];
            $editing_item['favorite_film'] = $request->data['favorite_film'];
            $editing_item['favorite_outdoor'] = $request->data['favorite_outdoor'];
            $editing_item['favorite_product'] = $request->data['favorite_product'];
            $editing_item['interested_event'] = $request->data['interested_event'];

            $editing_item -> save();
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_user = User::where('id','=',$request->user_id)->first();
        DB::table('user_role')->where('user_id','=',$deleted_user->id)->delete();
        $deleted_user -> delete();
    }

    public function get_user_data()
    {
        return auth()->user();
    }
}

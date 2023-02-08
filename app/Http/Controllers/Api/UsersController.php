<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;
use Mail;
use Hash;

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

    public function create_user_by_admin(Request $request)
    {
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
            User::create([
                'name'     => $request['data']['name'],
                'surname'    => $request['data']['surname'],

                'country'    => $request['data']['country'],
                'city'    => $request['data']['city'],

                'phone_number'    => $request['data']['phone_number'],
                'email'    => $request['data']['email'],

                'password' => bcrypt($request['data']['password']),
            ]);
        }
    }

    public function del_user(Request $request)
    {
        $deliting_user = User::where('id',strip_tags($request->user_id))->first();

        // dd($deliting_user);

        if (Auth::user()->id != $deliting_user->id) {

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
            // dd(Auth::user()->id);

            if(!user_notification::where('user_id', '=', Auth::user()->id)->first()){
                $new_notification_item =  new user_notification();
    
                $new_notification_item['user_id'] = Auth::user()->id;

                if(isset($request->data['add_new_gym'])){ $new_notification_item['add_new_gym'] = $request->data['add_new_gym']; }
                if(isset($request->data['news'])){ $new_notification_item['news'] = $request->data['news']; }
                if(isset($request->data['add_new_ice_spot'])){ $new_notification_item['add_new_ice_spot'] = $request->data['add_new_ice_spot']; }
                if(isset($request->data['add_new_outdoor_spot'])){ $new_notification_item['add_new_outdoor_spot'] = $request->data['add_new_outdoor_spot']; }
                if(isset($request->data['add_new_product'])){ $new_notification_item['add_new_product'] = $request->data['add_new_product']; }
                if(isset($request->data['add_new_sector'])){ $new_notification_item['add_new_sector'] = $request->data['add_new_sector']; }
                if(isset($request->data['add_new_service'])){ $new_notification_item['add_new_service'] = $request->data['add_new_service']; }
                if(isset($request->data['add_new_techtip'])){ $new_notification_item['add_new_techtip'] = $request->data['add_new_techtip']; }
                if(isset($request->data['favorite_film'])){ $new_notification_item['favorite_film'] = $request->data['favorite_film']; }
                if(isset($request->data['favorite_outdoor'])){ $new_notification_item['favorite_outdoor'] = $request->data['favorite_outdoor']; }
                if(isset($request->data['favorite_product'])){ $new_notification_item['favorite_product'] = $request->data['favorite_product']; }
                if(isset($request->data['interested_event'])){ $new_notification_item['interested_event'] = $request->data['interested_event']; }
    
                $new_notification_item -> save();
            }
            else{
                $edit_notification_item =  user_notification::where('user_id', '=', Auth::user()->id)->first();
    
                $edit_notification_item['add_new_gym'] = $request->data['add_new_gym'];
                $edit_notification_item['news'] = $request->data['news'];
                $edit_notification_item['add_new_ice_spot'] = $request->data['add_new_ice_spot'];
                $edit_notification_item['add_new_outdoor_spot'] = $request->data['add_new_outdoor_spot'];
                $edit_notification_item['add_new_product'] = $request->data['add_new_product'];
                $edit_notification_item['add_new_sector'] = $request->data['add_new_sector'];
                $edit_notification_item['add_new_service'] = $request->data['add_new_service'];
                $edit_notification_item['add_new_techtip'] = $request->data['add_new_techtip'];
                $edit_notification_item['favorite_film'] = $request->data['favorite_film'];
                $edit_notification_item['favorite_outdoor'] = $request->data['favorite_outdoor'];
                $edit_notification_item['favorite_product'] = $request->data['favorite_product'];
                $edit_notification_item['interested_event'] = $request->data['interested_event'];
    
                $edit_notification_item -> save();
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

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use App\Models\Role;
use App\Models\user_role;
use Auth;
use DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function users_list(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        
        if (view()->exists('user.data_table')) {
            
            $users = User::all();
            $roles = Role::all();
            
            $data = [
            'title'=>'Users and Roles',
            
            'table_1'=>$users,
            'table_1_name' => 'Users',
            // 'table_1_add_url'=>'sectorAdd',
            // 'table_1_edit_url'=>'users/sector_edit_form/',
            'table_1_get_route'=>'users/get_all_users_data',
            'table_1_del'=>"/users/del_user/",
            
            'table_2' => $roles,
            'table_2_name' => 'Roles',
            // 'table_2_add_url'=>'routeAdd',
            // 'table_2_edit_url'=>'users/route_edit_form/',
            'table_2_get_route'=>'users/get_roles_data',
            'table_2_del'=>"/users/route_delete/",

            'page_name' => 'Users and Roles',
            'active' => 'Users',
            ];
            return view('user.data_table',$data);
        }
        abort(404);
    }
     
 
    /**
     * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function options_index(Request $request)
    {
        $request->user()->authorizeRoles(['user', 'manager', 'admin']);
        
        if (view()->exists('user.options')) {
             
            $users = User::all();

            if(Auth::user()->hasRole('admin')){
                $status = 'Admin';
            }
            elseif(Auth::user()->hasRole('manager')){
                $status = 'Content manager';
            }
            else{
                $status = 'User';
            }
            // dd($status);
            $data = [
                'status' => $status,
                'title'=>'options',
                'table_1'=>$users,
                'table_1_edit_url'=>'userDel',
                'table_1_name'=>'Options',
                'user_status'=>'',
                'page_name' => 'Options',
                'active' => 'User',
                'page_route' => 'events_page',
                'no_edit' => '1',
            ];
            return view('user.options',$data);
        }
        abort(404);
    }


    /**
     * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function get_user_data()
    {
        $auth_uset_id = Auth::user()->id;
        return User::where('id','=',$auth_uset_id)->get();
    }

    public function get_all_users_data()
    {
        return User::get();
    }
    public function get_roles_data()
    {
        return Role::get();
    }

    /**
     * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(user $user, Request $request)
    {
        // $user_info = $request -> all();
        
        // dd($user_info);
        
        // foreach($user_info as $x => $x_value){
        //     $update = \App\User::where('id', '=', $x)->update(['name' => $x_value]);
        // }
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
     public function user_info_update(Request $request){
        $request->user()->authorizeRoles(['user', 'manager', 'admin']);
        //  dd($request->id);
        //  if ($request->isMethod('patch')) {
            $user = user::find($request->id);
            $user->name = $request['name'];
            $user->surname = $request['surname'];
            $user->email = $request['email'];
            $user->phone_number = $request['phone_number'];
            $user->country = $request['country'];
            $user->city = $request['city'];
            // $user->password = bcrypt(request('password'));
            // dd($user);
            $user->update();
        // }
        // $2y$10$O7RsUfT2cZ3hZQ58G7KxEejQCSodeLKPJxlILvvBSPuAJYOe7.spm = temo-1234
    }

    public function user_image_update(user $user, Request $request)
    {
        if ($request->hasFile('profile_pic')){   
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;

            // push image in folder
            $file->move(public_path('images/user_img'), $file_new_name);

            // $article = Article::where('id',strip_tags($last_global_article_id))->first();
            // $article['image']=$file_new_name;
            // $article -> save();

            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } 
        else{
            return response()->json(["message" => "Select image first."]);
        }
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
    public function destroy(user $user, Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        
        if ($request->isMethod('post')){
            $deleted_user = User::where('id','=',$request->user_id)->first();
            $deleted_user_role = DB::table('role_user')->where('user_id','=',$deleted_user->id)->first();
// dd($deleted_user_role->id);
            $deleted_user -> delete();
            $deleted_user_role -> delete();
        }
    }
}

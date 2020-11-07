<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use Auth;

class UserController extends Controller
{
     // use \App\User:HasRoles;
    
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
          
         if (view()->exists('user.article_list')) {
             
             $users = User::all();
             
             $data = [
                 'title'=>'Users',
                 'table_1'=>$users,
                 'table_1_edit_url'=>'userDel',
                 'table_1_name'=>'Users',
                 'user_status'=>'',
                 'page_name' => 'Users',
                 'active' => 'User',
                 'page_route' => 'events_page',
                 'no_edit' => '1',
             ];
             return view('user.article_list',$data);
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
             
             $data = [
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
     public function edit(user $user, Request $request){
 
         $request->user()->authorizeRoles(['user', 'manager', 'admin']);
         if ($request->isMethod('patch')) {
             if ($request['name'] != null) $user->name = $request['name'];
             if ($request['surname'] != null) $user->surname = $request['surname'];
             if ($request['email'] != null) $user->email = $request['email'];
             if ($request['phone_number'] != null) $user->phone_number = $request['phone_number'];
             if ($request['country'] != null) $user->country = $request['country'];
             if ($request['city'] != null) $user->city = $request['city'];
             if ($request['password'] != null)$user->password = bcrypt(request('password'));
 
             $user->update(); //сохраняем
             request()->session()->flash('success', 'Обновлено!'); //выводим сообщение про обновление
 
             return back();
         }
         // $2y$10$O7RsUfT2cZ3hZQ58G7KxEejQCSodeLKPJxlILvvBSPuAJYOe7.spm = temo-1234
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
         
         if ($request->isMethod('delete'))
             $user ->delete();
         return back();
     }
}

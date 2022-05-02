<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use App\User;
use App\Models\Following_users;
use App\Models\Role;

use App\Notifications\FollowingNotification;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::all();
    }

    public function get_following_users_list()
    {
        return following_users::all();
    }

    public function follow(Request $request)
    {
        // dd($request);
        Validator::make($request, [
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
        ]);

        if ($request -> isMethod('post')) {
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
            $user->notify(new FollowingNotification());
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
        DB::table('role_user')->where('user_id','=',$deleted_user->id)->delete();
        $deleted_user -> delete();
    }
}

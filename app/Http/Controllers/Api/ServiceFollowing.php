<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mail;

use App\Notifications\FollowingNotification;

use App\Models\User\Following_users;

class ServiceFollowing extends Controller
{
    public function follow(Request $request)
    {
        // dd($request->service_id);
        if ($request -> isMethod('post')) {
            if(Following_users::where('email','=',$request->email)->count()){
                return("You are already subscribed");
            }
            else{
                if (auth()->user() && $request->email == auth()->user()->email) {
                    return("You are olredy registred");
                }
                else {
                    $follow = new Following_users();
                    $follow['email']=$request->email;
                    $follow['service']=$request->service_id;
                    $follow -> save();

                    $EmailArray = array(
                        'email' => $request->email,
                        'unfollow_url' => env('APP_URL').'/unfollow/'.$follow->id,
                        'message' => 'Thenk you for following. Your following is completed',
                        'from_site' => $request->service_id,
                    );

                    Mail::to($request->email)->send(new FollowingNotification($EmailArray));

                    return("Tenk you for following! :) Plis check your emeil!");
                }
            }
        }
    }

    // public function get_followers_list()
    // {
    //     return following_users::latest('id')->get();
    // }

    // function del_follower(Request $request){
    //     $del_follower = following_users::where('id','=',$request->id)->count();

    //     if($del_follower != 0){
    //         $del_follower = following_users::where('id','=',$request->id)->first();
    //         $del_follower -> delete();
    //         return 'Unfollow completed!';
    //     }
    //     else if($del_follower == 0){
    //         return 'Email whith this ID is not finded!';
    //     }
    // }

    // public function get_following_users_list()
    // {
    //     return following_users::latest('id')->get();
    // }
}
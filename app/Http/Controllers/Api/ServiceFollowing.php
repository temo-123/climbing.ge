<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Mail;

use App\Notifications\FollowingNotification;

use App\Models\User;
use App\Models\User\Following_users;
use App\Services\ReCaptchaV3Service;

class ServiceFollowing extends Controller
{
    public function follow(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $captcha = new ReCaptchaV3Service();
        if ($captcha->isConfigured()) {
            $token = $request->input('recaptcha_token');
            if (!$token || !$captcha->verify($token, $request->ip(), 0.5)) {
                return response()->json(['message' => 'reCAPTCHA verification failed. Please try again.'], 422);
            }
        }

        if ($request -> isMethod('post')) {
            // Checks against any registered account with this email, not just
            // the current session — a logged-out guest could otherwise enter a
            // registered user's email and pick up a duplicate guest-follow row
            // for someone who already has their own notification preferences.
            if (User::where('email', $request->email)->exists()) {
                return response()->json(['message' => "You're already registered with us, so there's no need to subscribe separately."], 422);
            }

            if(Following_users::where('email','=',$request->email)->count()){
                return response()->json(['message' => "You're already subscribed to updates from this site."], 422);
            }

            $follow = new Following_users();
            $follow['email']=$request->email;
            $follow['service']=$request->service_id;
            $follow -> save();

            $EmailArray = array(
                'email' => $request->email,
                'unfollow_url' => env('APP_URL').'/unfollow/'.$follow->id,
                'message' => 'Thanks for following! You will now receive updates by email.',
                'from_site' => $request->service_id,
            );

            Mail::to($request->email)->send(new FollowingNotification($EmailArray));

            return response()->json(['message' => "Thanks for following! Please check your email to confirm."]);
        }
    }

    function del_follower(Request $request){
        $del_follower = following_users::where('id','=',$request->id)->count();

        if($del_follower != 0){
            $del_follower = following_users::where('id','=',$request->id)->first();
            $del_follower -> delete();
            return 'Unfollow completed!';
        }
        else if($del_follower == 0){
            return 'Email whith this ID is not finded!';
        }
    }
}
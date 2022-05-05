<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Socialite;
use App\Services\SocialFacebookAccountService;

class SocialAuthFacebookController extends Controller
{
    // /**
    //  * Create a redirect method to facebook api.
    //  *
    //  * @return void
    //  */
    
    // public function redirect()
    // {
    //     return Socialite::driver('facebook')->redirect();
    // }


    // /**
    //  * Return a callback method from facebook api.
    //  *
    //  * @return callback URL from facebook
    //  */

    // public function callback(SocialFacebookAccountService $service)
    // {
    //     $user = $service->createOrGetUser(Socialite::driver('facebook')->user());
    //     auth()->login($user);
    //     return redirect()->to('/home');
    // }

    public function redirectFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback()
    {
        try {
        
            $user = Socialite::driver('facebook')->user();
         
            $finduser = User::where('facebook_id', $user->id)->first();
        
            if($finduser){
         
                Auth::login($finduser);
        
                return redirect()->intended('dashboard');
         
            }else{
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'facebook_id'=> $user->id,
                    'password' => encrypt('Test123456')
                ]);
        
                Auth::login($newUser);
        
                return redirect()->intended('dashboard');
            }
        
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

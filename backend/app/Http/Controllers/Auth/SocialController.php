<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Socialite;
use Hash;
use Auth;
use Redirect;
use Validator;
use Password;
use Str;

use App\Models\user_notification;
use App\Models\User_role;
use App\Models\Role;
use App\Models\Social_account;

use App\Models\User;

class SocialController extends Controller
{
    public function redirect($provider)
    {
        // dd(Socialite::driver($provider));
        // return Socialite::driver($provider)->redirect();

        // return Socialite::driver($provider)->stateless()->redirect();
        $url = Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
        // dd($url);

        return response()->json(["url" => $url]);
    }

    public function callback($provider, Request $request)
    {
        // dd($provider, $request); 
        $social_user = Socialite::driver($provider)->stateless()->user();
        // dd($social_user->id); 
        $user = User::where(['email' => $social_user->getEmail()])->first();
        // dd($user);

        if(!$social_user->token){
            return response()->json(['Token error'], 500);
        }
        else{
            if($user){
                Auth::login($user);
                // return Redirect::to('/');
                // return response()->json("login");
                return response()->json(['status' => 'login']);
            }
            else{

                // $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                // $pieces = [];
                // $max = mb_strlen($stringSpace, '8bit') - 1;
                // for ($i = 0; $i < 16; ++ $i) {
                //     $pieces[] = $stringSpace[random_int(0, $max)];
                // }
                // $rendom_password = implode('', $pieces);
                

                $new_user = User::create([
                    'name'      => $social_user->getName(),
                    'email'     => $social_user->getEmail(),
                    'image'     => $social_user->getAvatar(),
                    // 'password'  => Hash::make($rendom_password)
                    'password'  => Hash::make(Str::random(10))
                ]);

                // dd((int) $new_user->id, (int) $social_user->id, $provider);

                // $new_social_provaider = Social_account::create([
                //     // 'provider' => $provider,
                //     // 'provider_user_id' => (int) $social_user->id,
                //     'user_id' => (int) $new_user->id
                // ]);

                $new_social_provaider =  new Social_account();

                $new_social_provaider['provider'] = $provider;
                $new_social_provaider['provider_user_id'] = (int) $social_user->id;
                $new_social_provaider['user_id'] = (int) $new_user->id;

                $new_social_provaider -> save();



                $this -> create_user_permissions_and_notificationes($new_user->id);
                
                // return Redirect::to('/create_password/' . $new_user->email);
                // return response()->json(["" "registratione");
                return response()->json(['status' => 'registratione', 'new_user_email' => $new_user->email]);
            }
        }
    }

    private function create_user_permissions_and_notificationes(int $user_id)
    {
        if(user_notification::where('user_id', '=', $user_id)->count() == 0){
            $new_notification_item =  new user_notification();

            $new_notification_item['user_id'] = $user_id;

            $new_notification_item['add_new_gym'] = 1;
            $new_notification_item['news'] = 1;
            $new_notification_item['add_new_ice_spot'] = 1;
            $new_notification_item['add_new_outdoor_spot'] = 1;
            $new_notification_item['add_new_product'] = 1;
            $new_notification_item['add_new_sector'] = 1;
            $new_notification_item['add_new_service'] = 1;
            $new_notification_item['add_new_techtip'] = 1;
            $new_notification_item['favorite_film'] = 1;
            $new_notification_item['favorite_outdoor'] = 1;
            $new_notification_item['favorite_product'] = 1;
            $new_notification_item['interested_event'] = 1;

            $new_notification_item -> save();
        }

        if(User_role::where('user_id', '=', $user_id)->count() == 0){
            $new_permission_item =  new User_role();

            $new_permission_item['user_id'] = (int) $user_id;
            $new_permission_item['role_id'] = Role::where('slug', '=', 'user')->first()->id; // ID 1 in role tab is a user

            $new_permission_item -> save();
        }
    }

    public function create_password(Request $request)
    {
        $validator = Validator::make($request->data, [
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (!$validator) {
            return $validator->messages();
        }
        else{
            User::where('email', '=', $request->email)->update(['password'=> Hash::make($request->data['password'])]);
        }
    }
}

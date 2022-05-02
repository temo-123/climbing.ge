<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Notifications\LoginEmailNotification;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function login(Request $request){
    //     $fields = $request->validate([
    //         'email'=>'required|string|email',
    //         'password' =>'required|confirmed'
    //     ]);
    //     //check email
    //     $user = User::where('email',$fields['email'])->first();
    //     //check password
    //     if(!$user || !Hash::check($fields['password'],$user->password)){
    //         return response(['status'=>false,'message'=>'invalid email or password'],401);
    //     }

    //     //create token
    //     $token = $user->createToken('myapptoken')->plainTextToken;

    //     $response = [
    //         'status'=>true,
    //         'message'=>'Login successful!',
    //         'data' =>[
    //             'user'=>$user,
    //             'token'=>$token
    //         ]
    //     ];
    //     return response($response,201);
    // }
}

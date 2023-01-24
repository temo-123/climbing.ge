<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Notifications\Notifiable;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

// use App\Notifications\WelcomeEmailNotification;

use App\Models\Role;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use Notifiable;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:25'],
            'surname' => ['required', 'string', 'max:55'],

            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],

            'phone_number' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // $token = $data['g-recaptcha-response'];
        // dd('dd');
        $capcha = true;

        if($capcha){
            $user = User::create([
                'name'     => $data['name'],
                'surname'    => $data['surname'],

                'country'    => $data['country'],
                'city'    => $data['city'],

                'phone_number'    => $data['phone_number'],
                'email'    => $data['email'],

                'password' => bcrypt($data['password']),
            ]);

            // $user->roles()->attach(Role::where('name', 'user')->first());

            return $user;
        }
        else{
            return ('Captcha is feild');
        }
    }
}

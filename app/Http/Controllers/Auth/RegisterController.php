<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Notifications\Notifiable;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

use App\Models\User\Role;
use App\Models\User\User_role;
use App\Models\User\user_notification;

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
        $this->middleware('guest')->except('apiRegister');
    }

    public function apiRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $this->create($request->all());

        event(new Registered($user));

        auth()->login($user);

        $user->tokens()->where('name', 'authToken')->delete();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => $user,
            'message' => 'Registration successful. Please check your email to verify your account.'
        ], 201);
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
        $user = User::create([
            'name'         => $data['name'],
            'surname'      => $data['surname'],
            'country'      => $data['country'],
            'city'         => $data['city'],
            'phone_number' => $data['phone_number'],
            'email'        => $data['email'],
            'password'     => bcrypt($data['password']),
        ]);

        if (user_notification::where('user_id', $user->id)->count() === 0) {
            user_notification::create([
                'user_id'              => $user->id,
                'add_new_gym'          => 1,
                'news'                 => 1,
                'add_new_ice_spot'     => 1,
                'add_new_outdoor_spot' => 1,
                'add_new_product'      => 1,
                'add_new_sector'       => 1,
                'add_new_service'      => 1,
                'add_new_techtip'      => 1,
                'favorite_film'        => 1,
                'favorite_outdoor'     => 1,
                'favorite_product'     => 1,
                'interested_event'     => 1,
            ]);
        }

        if (User_role::where('user_id', $user->id)->count() === 0) {
            User_role::create([
                'user_id' => $user->id,
                'role_id' => Role::where('slug', 'user')->first()->id,
            ]);
        }

        return $user;
    }
}

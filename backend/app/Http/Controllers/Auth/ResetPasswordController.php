<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;

use Illuminate\Http\Request;

use Validator;
use Password;
use Hash;
use Str;

use App\Models\User;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function reset_password(Request $request)
    {
        $validator = Validator::make($request->data, [
            'password' => ['required', 'confirmed', 'min:8'],
            'token' => ['required'],
        ]);

        if (!$validator) {
            return $validator->messages();
        }
        else{
            $user = User::where('id', '=', $request->data['id'])->first();

            $status = Password::reset(
                $request->only('data')['data'],

                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->data['password']),
                        'remember_token' => Str::random(60)
                    ])->save();
                }
            );
            
            if ($status === Password::PASSWORD_RESET) {
                return response()->json(['message' => trans($status)], 200);
            }
            else{
                return response()->json(['message' => 'Password updating error.'], 500);
            }
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

use Illuminate\Http\Request;
use Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function send_forget_mail(Request $request)
    {
        // $activ_user = User::where('email', '=', $request->email)->first();
        // dd($request->only('email', 'password', 'password_confirmation', 'token'));
        // if ($activ_user) {
        //     $status = Password::reset(
        //         $request->only('email', 'password', 'password_confirmation', 'token'),
        //         function ($user) use ($request) {
        //             $user->forceFill([
        //                 'password' => Hash::make($request->password),
        //                 'remember_token' => Str::random(60),
        //             ])->save();
    
        //             $user->tokens()->delete();
    
        //             event(new PasswordReset($user));
        //         }
        //     );
    
        // } 
        // else {
        //     return response()->json(['message' => 'User of this mail not fined.'], 422);
        // }

        $activ_user = User::where('email', '=', $request->email)->first();

        if ($activ_user) {
            $request->validate([
                'email' => ['required', 'email'],
            ]);

            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', trans($status));
            }

            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => trans($status)]);
        }
        else {
            return response()->json(['message' => 'User of this mail not fined.'], 422);
        }
    }

    public function forget_password(Request $request)
    {
        echo 'ddd';
    }
}

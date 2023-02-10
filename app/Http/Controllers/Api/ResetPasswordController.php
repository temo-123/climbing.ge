<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class ResetPasswordController extends Controller
{
    public function send_reseting_mail(Request $request)
    {
        $activ_user = User::where('email', '=', $request->email)->first();

        if ($activ_user) {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();
    
                    $user->tokens()->delete();
    
                    event(new PasswordReset($user));
                }
            );
    
        } 
        else {
            return response()->json(['message' => 'User of this mail not fined.'], 422);
        }

    }

    public function reset_password(Request $request)
    {
        echo 'ddd';
    }
}

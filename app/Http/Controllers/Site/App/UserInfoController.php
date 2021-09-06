<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

class UserInfoController extends Controller
{
    public function get_user_info(Request $request)
    {
        $user_status = '';
        $user_id = '';
        $user_name = '';
        $user_surname = '';
        $user_email = '';

        if (Auth::guest()) {
            $user_status = 'gest';
        }
        else {
            $user_status = 'user';
            $user_id = Auth::user() -> id;
            $user_id = Auth::user() -> name;
            $user_id = Auth::user() -> surname;
            $user_id = Auth::user() -> email;
        }

        $data = [
            "user_status" => $user_status,
            "user_id" => $user_id,
            "user_name" => $user_name,
            "user_surname" => $user_surname,
            "user_email" => $user_email,
        ];

        return $data;
    }
}

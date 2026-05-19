<?php

namespace App\Services\Abstract;

use Illuminate\Http\Request;
use Notification;

use App\Notifications\comments\CommentEmailVarificationNotification;

class EmailVarificationeService
{
    public static function varificate_email($email, $id) {
        $token = hash_hmac('sha256', $email . '|' . $id, config('app.key'));
        $info = [
            "host" => $_SERVER['HTTP_HOST'],
            "id" => $id,
            "user_email" => $email,
            "token" => $token,
        ];
        Notification::route('mail', $email)->notify(new CommentEmailVarificationNotification($info));

        return ("Thank you for comment. Please go to your email to confirm it. Your comment will not appear until you do this!");
    }
}
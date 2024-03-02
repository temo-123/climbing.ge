<?php

namespace App\Services\Abstract;

use Illuminate\Http\Request;

use App\Notifications\comments\CommentEmailVarificationNotification;

class EmailVarificationeService
{
    public static function varificate_email($email, $id) {
        $info = [
            "host" => $_SERVER['HTTP_HOST'],
            "id" => $id,
            "user_email" => $email
        ];
        Notification::route('mail', $email)->notify(new CommentEmailVarificationNotification($info));

        return ("Tenk you for comment. Please go to your email to confirm it. Your comment will not appear until you do this!");
    }
}
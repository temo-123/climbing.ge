<?php

namespace App\Http\Controllers\Api\Meil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Notifications\FollowingNotification;

class FollowingNotificationController extends Controller
{
    public function send_notification(Request $request)
    {
        dd($request);
        $this->message_validate($request);

        if($request->email != null)     $email = $request->email;         else $email = null;
        if($request->msg != null)       $msg = $request->msg;             else $msg = null;

        $from_site = "climbing.ge";
        
        $EmailArray = array(
            'email' => $email,
            'message' => $msg,
            'from_site' => $from_site
        );

        $send_mail = Site::latest()->get(["email"]);
        
        Mail::to($send_mail)->send(new FollowingNotification($EmailArray));
        
        return ["status" => "Notification is sendid!"];
    }

    public function message_validate($request)
    {
        $request->validate([
            'email' => 'required|email',
            'msg' => 'required',
        ]);
    }
}

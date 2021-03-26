<?php

namespace App\Http\Controllers\Shop\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Models\Site;
use App\Mail\Message;
use Validate;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $this->message_validate($request);

        if($request->name != null)      $name = $request->name;           else $name = null;
        if($request->surname != null)   $surname = $request->surname;     else $surname = null;
        if($request->email != null)     $email = $request->email;         else $email = null;
        if($request->num != null)       $num = $request->num;             else $num = null;
        if($request->country != null)   $country = $request->country;     else $country = null;
        if($request->msg != null)       $msg = $request->msg;             else $msg = null;

        $from_site = "shop.climbing.ge";
        
        $EmailArray = array(
            'name' => $name,
            'surname' => $surname,
            'email' => $email,
            'num' => $num,
            'country' => $country,
            'message' => $msg,
            'from_site' => $from_site
        );

        $send_mail = Site::latest()->get(["email"]);
        
        Mail::to($send_mail)->send(new Message($EmailArray));
        
        return ["status" => "Your message is sendid!"];
    }
    public function message_validate($request)
    {
        $request->validate([
            'name' => 'required|max:25',
            'surname' => 'required|max:55',
            'email' => 'required|email',
            'num' => 'required',
            'country' => 'required',
            'msg' => 'required',
        ]);
    }
}

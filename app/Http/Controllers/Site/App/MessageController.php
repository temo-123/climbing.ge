<?php

namespace App\Http\Controllers\Site\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Mail;

use App\Models\Site;
use App\Mail\Message;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        // dd($request);
        // $token = $request -> input('g-recaptcha-response');
        // if ($token != NULL) {

            if($request->name != null)      $name = $request->name;           else $name = null;
            if($request->surname != null)   $surname = $request->surname;     else $surname = null;
            if($request->email != null)     $email = $request->email;         else $email = null;
            if($request->num != null)       $num = $request->num;             else $num = null;
            if($request->country != null)   $country = $request->country;     else $country = null;
            if($request->msg != null)       $msg = $request->msg;             else $msg = null;
            
            $EmailArray = array(
    	        'name' => $name,
    	        'surname' => $surname,
    	        'email' => $email,
    	        'num' => $num,
    	        'country' => $country,
    	        'message' => $msg,
            );

            // dd($EmailArray);
            $send_mail = Site::latest()->get(["email"]);
            
            Mail::to($send_mail)->send(new Message($EmailArray));
            
            // return redirect()->route('about_page')->with('status','Message sent');
            return ["status" => "Your message is sendid!"];
        // }
        // else return Back()->with('status','Fill captcha!');
        // else return ["status" => "Error! you have a problem whis send mail!"];
    }
}

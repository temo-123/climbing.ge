<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use Mail;

use App\Models\User;
use App\Models\User\user_notification;

class UserOptionController extends Controller
{
    
    // public function get_user_data()
    // {
    //     return auth()->user();
    // }

    function get_selected_user_data(Request $request) {
        $user = User::where('id', '=', $request->user_id)->first();
        // dd($user -> sites);
        $user -> sites;
        return $user;
    }

    public function user_info_update(Request $request)
    {
        if (Auth::user()) {
            if(Auth::user()->id == $request->user_id){
                $editing_item = User::where('id', '=', Auth::user()->id)->first();

                $editing_item['name'] = $request->data['name'];
                $editing_item['surname'] = $request->data['surname'];
                $editing_item['country'] = $request->data['country'];
                $editing_item['city'] = $request->data['city'];
                $editing_item['phone_number'] = $request->data['phone_number'];
                $editing_item['email'] = $request->data['email'];

                $editing_item -> update();
            }
            else{
                return 'Error';
            }
        }
        else{
            return 'Plees login';
        }
    }
    
    public function get_user_notification_data(Request $request)
    {
        if (Auth::user()) {
            $record = user_notification::where('user_id', '=', Auth::user()->id)->first();
            if ($record) {
                return json_decode($record->notification_type, true) ?? (object)[];
            }
            return (object)[];
        }
        return 'Plees login';
    }

    public function update_user_notification_data(Request $request)
    {
        if (Auth::user()) {
            $data = $request->data ?? [];
            user_notification::updateOrCreate(
                ['user_id' => Auth::user()->id],
                ['notification_type' => json_encode($data)]
            );
        } else {
            return 'Plees login';
        }
    }
}

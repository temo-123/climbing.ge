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
            return user_notification::where('user_id', '=', Auth::user()->id)->first();
        }
        else{
            return 'Plees login';
        }
    }

    public function update_user_notification_data(Request $request)
    {
        if (Auth::user()) {
            // dd(Auth::user()->id);

            if(!user_notification::where('user_id', '=', Auth::user()->id)->first()){
                $new_notification_item =  new user_notification();
    
                $new_notification_item['user_id'] = Auth::user()->id;

                if(isset($request->data['add_new_gym'])){ $new_notification_item['add_new_gym'] = $request->data['add_new_gym']; }
                if(isset($request->data['news'])){ $new_notification_item['news'] = $request->data['news']; }
                if(isset($request->data['add_new_ice_spot'])){ $new_notification_item['add_new_ice_spot'] = $request->data['add_new_ice_spot']; }
                if(isset($request->data['add_new_outdoor_spot'])){ $new_notification_item['add_new_outdoor_spot'] = $request->data['add_new_outdoor_spot']; }
                if(isset($request->data['add_new_product'])){ $new_notification_item['add_new_product'] = $request->data['add_new_product']; }
                if(isset($request->data['add_new_sector'])){ $new_notification_item['add_new_sector'] = $request->data['add_new_sector']; }
                if(isset($request->data['add_new_service'])){ $new_notification_item['add_new_service'] = $request->data['add_new_service']; }
                if(isset($request->data['add_new_techtip'])){ $new_notification_item['add_new_techtip'] = $request->data['add_new_techtip']; }
                if(isset($request->data['favorite_film'])){ $new_notification_item['favorite_film'] = $request->data['favorite_film']; }
                if(isset($request->data['favorite_outdoor'])){ $new_notification_item['favorite_outdoor'] = $request->data['favorite_outdoor']; }
                if(isset($request->data['favorite_product'])){ $new_notification_item['favorite_product'] = $request->data['favorite_product']; }
                if(isset($request->data['interested_event'])){ $new_notification_item['interested_event'] = $request->data['interested_event']; }
    
                $new_notification_item -> save();
            }
            else{
                $edit_notification_item =  user_notification::where('user_id', '=', Auth::user()->id)->first();
    
                $edit_notification_item['add_new_gym'] = $request->data['add_new_gym'];
                $edit_notification_item['news'] = $request->data['news'];
                $edit_notification_item['add_new_ice_spot'] = $request->data['add_new_ice_spot'];
                $edit_notification_item['add_new_outdoor_spot'] = $request->data['add_new_outdoor_spot'];
                $edit_notification_item['add_new_product'] = $request->data['add_new_product'];
                $edit_notification_item['add_new_sector'] = $request->data['add_new_sector'];
                $edit_notification_item['add_new_service'] = $request->data['add_new_service'];
                $edit_notification_item['add_new_techtip'] = $request->data['add_new_techtip'];
                $edit_notification_item['favorite_film'] = $request->data['favorite_film'];
                $edit_notification_item['favorite_outdoor'] = $request->data['favorite_outdoor'];
                $edit_notification_item['favorite_product'] = $request->data['favorite_product'];
                $edit_notification_item['interested_event'] = $request->data['interested_event'];
    
                $edit_notification_item -> save();
            }
        }
        else{
            return 'Plees login';
        }
    }
}

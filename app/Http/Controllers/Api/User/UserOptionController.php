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
    /**
     * Every key the notification preferences UI can toggle. Used both to fill
     * in defaults for users who never saved preferences yet (or saved before a
     * key was added) and to whitelist what update_user_notification_data will
     * persist, since notification_type is stored as free-form JSON.
     */
    private const NOTIFICATION_KEYS = [
        'add_new_gym', 'add_new_ice_spot', 'add_new_outdoor_spot', 'add_new_product',
        'add_new_sector', 'add_new_service', 'add_new_techtip', 'add_new_tour',
        'add_new_mount', 'add_new_summit', 'add_new_blog_post', 'add_new_film',
        'favorite_film', 'favorite_outdoor', 'favorite_product', 'interested_event',
        'news', 'sector_updated', 'news_updated', 'outdoor_spot_updated',
        'ice_spot_updated', 'techtip_updated', 'gym_updated', 'product_updated',
        'service_updated', 'tour_updated', 'mount_updated', 'summit_updated',
        'blog_post_updated', 'film_updated',
    ];


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
                $editing_item['my_bio'] = $request->data['my_bio'] ?? null;

                $allowedSocialKeys = ['facebook', 'instagram', 'youtube', 'website'];
                $socialLinks = [];
                foreach ($allowedSocialKeys as $key) {
                    $value = trim($request->data['social_links'][$key] ?? '');
                    if ($value !== '') {
                        $socialLinks[$key] = $value;
                    }
                }
                $editing_item['social_links'] = $socialLinks ?: null;

                if (in_array($request->data['lang'] ?? null, ['us', 'ka'])) {
                    $editing_item['lang'] = $request->data['lang'];
                }

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
        if (!Auth::user()) {
            return response()->json(['message' => 'Please login'], 401);
        }

        $record = user_notification::where('user_id', '=', Auth::user()->id)->first();
        $saved = $record->notification_type ?? [];

        $defaults = array_fill_keys(self::NOTIFICATION_KEYS, false);
        return response()->json(array_merge($defaults, array_intersect_key($saved, $defaults)));
    }

    public function update_user_notification_data(Request $request)
    {
        if (!Auth::user()) {
            return response()->json(['message' => 'Please login'], 401);
        }

        $data = $request->data ?? [];
        $whitelisted = [];
        foreach (self::NOTIFICATION_KEYS as $key) {
            $whitelisted[$key] = filter_var($data[$key] ?? false, FILTER_VALIDATE_BOOLEAN);
        }

        user_notification::updateOrCreate(
            ['user_id' => Auth::user()->id],
            ['notification_type' => $whitelisted]
        );

        return response()->json(['message' => 'Notification preferences saved']);
    }
}

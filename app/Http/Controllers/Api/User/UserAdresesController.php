<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Auth;

use App\Models\User\User_adreses;

class UserAdresesController extends Controller
{

    public function get_user_adreses(Request $request)
    {
        if (Auth::user()) {
            // return Auth::user();
            return User_adreses::where('user_id', '=', Auth::user()->id)->get();
        }
        else{
            dd('Plees login');
        }
    }

    public function get_activ_adres(Request $request)
    {
        return User_adreses::where('id', '=', $request->adres_id)->where('user_id', '=', Auth::user()->id)->first();
    }

    public function add_user_adreses(Request $request)
    {
        // dd($request->adding_data['demo_name']);

        // $editing_item = User_adreses::where('id', '=', $request->adres_id)->first();
        $editing_item = new User_adreses;

        $user_id = Auth::user()->id;

        $editing_item['name'] = $request->adding_data['demo_name'];
        $editing_item['region_id'] = $request->adding_data['region_id'];
        $editing_item['city'] = $request->adding_data['city'];
        $editing_item['strit'] = $request->adding_data['strit'];
        $editing_item['number'] = $request->adding_data['number'];
        $editing_item['floor'] = $request->adding_data['floor'];
        $editing_item['flat'] = $request->adding_data['flat'];
        $editing_item['entrance'] = $request->adding_data['entrance'];
        $editing_item['zip_code'] = $request->adding_data['zip_code'];
        $editing_item['map'] = $request->adding_data['map'] ?? null;

        // A user always has exactly one default address: the first address
        // they ever add becomes it automatically, and explicitly requesting
        // default on a later address demotes every other address of theirs.
        $wants_default = filter_var($request->adding_data['is_default'] ?? false, FILTER_VALIDATE_BOOLEAN);
        $is_first_address = !User_adreses::where('user_id', $user_id)->exists();
        if ($wants_default || $is_first_address) {
            User_adreses::where('user_id', $user_id)->update(['is_default' => false]);
            $editing_item['is_default'] = true;
        } else {
            $editing_item['is_default'] = false;
        }

        $editing_item['user_id'] = $user_id;

        $editing_item->save();

        return $editing_item;
    }

    public function edit_adres(Request $request)
    {
        if (Auth::user()) {
        // dd($request->adres_id);
            $user_id = Auth::user()->id;
            $editing_item = User_adreses::where('id', '=', $request->adres_id)->where('user_id', '=', $user_id)->first();
            if (!$editing_item) {
                return response()->json(['error' => 'Address not found'], 404);
            }

            $editing_item['name'] = $request->data['demo_name'];
            $editing_item['region_id'] = $request->data['region_id'];
            $editing_item['city'] = $request->data['city'];
            $editing_item['strit'] = $request->data['strit'];
            $editing_item['number'] = $request->data['number'];
            $editing_item['floor'] = $request->data['floor'];
            $editing_item['flat'] = $request->data['flat'];
            $editing_item['entrance'] = $request->data['entrance'];
            $editing_item['zip_code'] = $request->data['zip_code'];
            $editing_item['map'] = $request->data['map'] ?? $editing_item['map'];

            if (array_key_exists('is_default', $request->data)) {
                $wants_default = filter_var($request->data['is_default'], FILTER_VALIDATE_BOOLEAN);
                if ($wants_default) {
                    User_adreses::where('user_id', $user_id)->where('id', '!=', $editing_item->id)->update(['is_default' => false]);
                }
                $editing_item['is_default'] = $wants_default;
            }

            $editing_item -> save();
        }
        else{
            return 'Plees login';
        }

        // dd($request->eding_data.adres_id);
    }

    // Explicitly marks one address as the user's default, demoting every
    // other address they own. Used by any page that lets a user manage
    // addresses without going through the full edit form.
    public function set_default_adres(Request $request, $adres_id)
    {
        $user_id = Auth::user()->id;
        $address = User_adreses::where('id', $adres_id)->where('user_id', $user_id)->first();
        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        User_adreses::where('user_id', $user_id)->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        return response()->json(['status' => 'ok']);
    }

    public function get_editing_adres(Request $request)
    {
        if (Auth::user()) {
            // return User_adreses::where('user_id', '=', Auth::user()->id)->get();
            return User_adreses::where('id', '=', $request->adres_id)->where('user_id', '=', Auth::user()->id)->first();
        }
        else{
            dd('Plees login');
        }
    }

    public function del_user_adreses(Request $request)
    {
        if ($request->isMethod('delete')) {
            $item = User_adreses::where('id', '=', $request->adres_id)->where('user_id', '=', Auth::user()->id)->first();
            if ($item) {
                $item -> delete();
            }
        }
    }
}

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
        return User_adreses::where('id', '=', $request->adres_id)->first();
    }

    public function add_user_adreses(Request $request)
    {
        // dd($request->adding_data['demo_name']);

        // $editing_item = User_adreses::where('id', '=', $request->adres_id)->first();
        $editing_item = new User_adreses;

        $editing_item['name'] = $request->adding_data['demo_name'];
        $editing_item['region_id'] = $request->adding_data['region_id'];
        $editing_item['city'] = $request->adding_data['city'];
        $editing_item['strit'] = $request->adding_data['strit'];
        $editing_item['number'] = $request->adding_data['number'];
        $editing_item['floor'] = $request->adding_data['floor'];
        $editing_item['flat'] = $request->adding_data['flat'];
        $editing_item['entrance'] = $request->adding_data['entrance'];
        $editing_item['zip_code'] = $request->adding_data['zip_code'];

        $editing_item['user_id'] = Auth::user()->id;

        $editing_item -> save();
    }

    public function edit_adres(Request $request)
    {
        if (Auth::user()) {
        // dd($request->adres_id);
            $editing_item = User_adreses::where('id', '=', $request->adres_id)->first();

            $editing_item['name'] = $request->data['demo_name'];
            $editing_item['region_id'] = $request->data['region_id'];
            $editing_item['city'] = $request->data['city'];
            $editing_item['strit'] = $request->data['strit'];
            $editing_item['number'] = $request->data['number'];
            $editing_item['floor'] = $request->data['floor'];
            $editing_item['flat'] = $request->data['flat'];
            $editing_item['entrance'] = $request->data['entrance'];
            $editing_item['zip_code'] = $request->data['zip_code'];

            $editing_item['user_id'] = Auth::user()->id;

            $editing_item -> save();
        }
        else{
            return 'Plees login';
        }

        // dd($request->eding_data.adres_id);
    }

    public function get_editing_adres(Request $request)
    {
        if (Auth::user()) {
            // return User_adreses::where('user_id', '=', Auth::user()->id)->get();
            return User_adreses::where('id', '=', $request->adres_id)->first();
        }
        else{
            dd('Plees login');
        }
    }
    
    public function del_user_adreses(Request $request)
    {
        if ($request->isMethod('delete')) {
            $item = User_adreses::where('id', '=', $request->adres_id)->first();
            $item -> delete();
        }
    }
}

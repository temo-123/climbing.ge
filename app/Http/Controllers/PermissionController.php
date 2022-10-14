<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

class PermissionController extends Controller
{
    public function permission(Request $request)
    {
        if(Auth::user()->hasRole('admin')){
            return 'admin';
        }
        elseif (Auth::user()->hasRole('manager')) {
            return 'manager';
        }
        elseif (Auth::user()->hasRole('ka_manager')) {
            return 'ka_manager';
        }
        elseif (Auth::user()->hasRole('ru_manager')) {
            return 'ru_manager';
        }
        elseif (Auth::user()->hasRole('us_manager')) {
            return 'us_manager';
        }
        elseif (Auth::user()->hasRole('seller')) {
            return 'seller';
        }
        else{
            return 'user';
        }
    }
}

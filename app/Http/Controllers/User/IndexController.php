<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(
            [
                'admin', 
                'manager', 
                'ka_manager', 
                'ru_manager', 
                'us_manager', 
                'seller', 
                'user'
            ]);

        $data = [
            "page_name" => "Home"
        ];
        return view('user.home', $data);
    }
  
}

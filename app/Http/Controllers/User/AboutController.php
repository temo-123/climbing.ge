<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;

class AboutController extends Controller
{
    public function index()
    {
        $about_us = site::get();
        $data = [
            'title'=>'site info',

            'table_1'=>$about_us,

            'table_1_name'=>'site info',
            
            'page_name' => 'site info',
            'active' => 'galley',
            'page_route' => 'outdoor_page',
        ];
        return view('user.about_us', $data);
    }
    public function edit()
    {
        # code...
    }
}

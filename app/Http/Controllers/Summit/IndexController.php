<?php

namespace App\Http\Controllers\Summit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request, $any = null)
    {
        return view('summit.index');
    }
}


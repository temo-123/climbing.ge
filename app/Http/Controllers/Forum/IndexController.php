<?php

namespace App\Http\Controllers\Forum;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index ()
    {
    	if (view()->exists('forum.index')) {
    		// $data = [
    		// 	'title'=>'Climbing store',
    		// ];
    		// return view('forum.index',$data);
    		return view('forum.index');
    	}
    	abort(404);
    }
}

<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index ()
    {
    	if (view()->exists('blog.index')) {
    		// $data = [
    		// 	'title'=>'Climbing store',
    		// ];
    		// return view('blog.index',$data);
    		return view('blog.index');
    	}
    	abort(404);
    }
}

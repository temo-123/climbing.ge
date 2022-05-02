<?php

namespace App\Http\Controllers\Films;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (view()->exists('films.index')) {
    		$data = [
    			'title'=>'Climbing store',
                
                'articles_link' => 'other_page',
                'image_dir' => 'films_img',
    		];
    		return view('films.index',$data);
            // return view('shop.index');
    	}
    	abort(404);
    }
}

<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Site;

class About_usController extends Controller
{
    public function about_us(Request $request)
    {
    	if (view()->exists('shop.about_us')) {
            $pages = Site::all();
        
            return view('shop.about_us', array(
                'about_us'=>1,
            )); 
        }
        abort(404);
    }
}

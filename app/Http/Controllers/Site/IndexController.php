<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Article;
use App\Models\Gallery_image;

use App\Services\GetArticlesService;

class IndexController extends Controller
{
    public function index()
    {
        if (view()->exists('site.index')) {
            return view('site.index');
    	}
    	abort(404);
    }
}

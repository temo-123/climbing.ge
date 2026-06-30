<?php

namespace App\Http\Controllers\Films;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (view()->exists('films.index')) {
            $seo = (new SeoService())->forFilms($request);
            return view('films.index', compact('seo'));
    	}
    	abort(404);
    }
}

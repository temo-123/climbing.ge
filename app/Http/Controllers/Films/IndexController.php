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
            // a missing article / product / … answers 404 (not a soft 404); the SPA shows its 404 page
            return response()->view('films.index', compact('seo'), $seo['status'] ?? 200);
    	}
    	abort(404);
    }
}

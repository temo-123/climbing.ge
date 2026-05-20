<?php

namespace App\Http\Controllers\Summit;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\SeoService;

class IndexController extends Controller
{
    public function index(Request $request, $any = null)
    {
        $seo = (new SeoService())->forSummit($request);
        return view('summit.index', compact('seo'));
    }
}

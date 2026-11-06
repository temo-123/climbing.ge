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
        // a missing article / product / … answers 404 (not a soft 404); the SPA shows its 404 page
            return response()->view('summit.index', compact('seo'), $seo['status'] ?? 200);
    }
}

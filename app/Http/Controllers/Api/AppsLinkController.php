<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apps_link;

class AppsLinkController extends Controller
{
    public function get_all()
    {
        return Apps_link::orderBy('keyword')->get(['keyword', 'link']);
    }
}

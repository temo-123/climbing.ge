<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Event;

class CompetitionController extends Controller
{
    public function get_all_competitions(Request $request)
    {
        return Event::where("category", "=", "competition")->latest('end_data')->get();
    }
}

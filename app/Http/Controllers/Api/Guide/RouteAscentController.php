<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\RouteAscent;

class RouteAscentController extends Controller
{
    /**
     * Public ascent list for one route — climber restricted to public-safe columns.
     */
    public function get_route_ascents($route_id)
    {
        return RouteAscent::where('route_id', $route_id)
            ->with('user:id,name,surname,image')
            ->orderByDesc('ascent_date')
            ->orderByDesc('id')
            ->get(['id', 'route_id', 'user_id', 'ascent_date', 'ascent_style', 'attempts', 'suggested_grade', 'comment', 'photo']);
    }
}

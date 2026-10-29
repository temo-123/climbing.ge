<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StravaService;
use Illuminate\Http\Request;

/**
 * Strava sends the browser back here after "Connect with Strava" (no token
 * in that request — the encrypted `state` says which user asked). Ends on the
 * user panel's My Ascents page with ?strava=connected|denied|error.
 */
class StravaCallbackController extends Controller
{
    public function callback(Request $request)
    {
        $back = rtrim(env('APP_SSH', 'https://') . env('USER_PAGE_URL', 'user.climbing.ge'), '/') . '/my_ascents?strava=';

        if ($request->filled('error')) return redirect($back . 'denied');

        $user_id = StravaService::user_from_state($request->state);
        if (!$user_id || !$request->filled('code') || !StravaService::configured()) return redirect($back . 'error');

        // activity:read_all is what lets us read the track
        $scope = (string) $request->scope;
        if (!str_contains($scope, 'activity:read')) return redirect($back . 'scope');

        try {
            StravaService::connect($user_id, $request->code, $scope);
        } catch (\Throwable $e) {
            return redirect($back . 'error');
        }
        return redirect($back . 'connected');
    }
}

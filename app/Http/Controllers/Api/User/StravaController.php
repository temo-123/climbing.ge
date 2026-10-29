<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\StravaConnection;
use App\Services\StravaService;
use Illuminate\Http\Request;
use RuntimeException;

/** The user's Strava connection (logged in). The OAuth callback is StravaCallbackController. */
class StravaController extends Controller
{
    public function status(Request $request)
    {
        $connection = StravaConnection::where('user_id', $request->user()->id)->first();
        return response()->json([
            'configured'   => StravaService::configured(),
            'connected'    => (bool) $connection,
            'athlete_name' => $connection?->athlete_name,
        ]);
    }

    public function connect_url(Request $request)
    {
        if (!StravaService::configured()) return response()->json(['message' => 'strava_not_configured'], 422);
        return response()->json(['url' => StravaService::authorize_url($request->user()->id)]);
    }

    // activities around a day (the ascent date): ±3 days
    public function activities(Request $request)
    {
        $request->validate(['date' => 'nullable|date']);
        $connection = StravaConnection::where('user_id', $request->user()->id)->first();
        if (!$connection) return response()->json(['message' => 'strava_not_connected', 'code' => 'strava_not_connected'], 422);

        $day = $request->date ? strtotime($request->date) : time();
        try {
            return response()->json(StravaService::activities($connection, $day - 3 * 86400, $day + 4 * 86400));
        } catch (RuntimeException $e) {
            if ($e->getMessage() === 'strava_unauthorized') $connection->delete();   // revoked on Strava's side
            return response()->json(['message' => $e->getMessage(), 'code' => $e->getMessage()], $e->getMessage() === 'strava_rate_limit' ? 429 : 422);
        }
    }

    public function disconnect(Request $request)
    {
        $connection = StravaConnection::where('user_id', $request->user()->id)->first();
        if ($connection) StravaService::disconnect($connection);
        return response()->json(['success' => true]);
    }
}

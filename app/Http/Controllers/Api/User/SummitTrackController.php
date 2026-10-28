<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\StravaConnection;
use App\Models\Summit\SummitAscentTrack;
use App\Services\StravaService;
use App\Services\SummitTrackService;
use Illuminate\Http\Request;
use RuntimeException;

/**
 * GPS track of one of the user's own summit ascents: upload a GPX file or
 * import a Strava activity → stats + "passes within 150 m of the summit".
 * docs/SUMMIT.md → GPS tracks.
 */
class SummitTrackController extends Controller
{
    // the user's own ascent, with its summit
    private function own_ascent(Request $request, $ascent_id)
    {
        return $request->user()->ascents()->with('summit', 'track')->find($ascent_id);
    }

    private function error(string $code, int $status = 422)
    {
        return response()->json(['message' => $code, 'code' => $code], $status);
    }

    private function save($ascent, Request $request, array $stats, string $source, ?array $points, ?int $activity_id)
    {
        $track = SummitAscentTrack::updateOrCreate(['ascent_id' => $ascent->id], [
            'user_id'            => $request->user()->id,
            'source'             => $source,
            'strava_activity_id' => $activity_id,
            'points'             => $points,
        ] + $stats);

        return response()->json($this->payload($ascent, $track));
    }

    private function payload($ascent, ?SummitAscentTrack $track): array
    {
        $summit = $ascent->summit;
        return [
            'track' => $track ? $track->summary() + [
                'closest_m'  => $track->closest_m,
                'started_at' => $track->started_at?->toIso8601String(),
                // Strava data stays with Strava (shown to its owner only, not stored)
                'points'     => $track->source === 'gpx' ? $track->points : null,
            ] : null,
            'summit' => $summit ? [
                'id'        => $summit->id,
                'title'     => $summit->title,
                'latitude'  => $summit->latitude,
                'longitude' => $summit->longitude,
                'height'    => $summit->height,
            ] : null,
            'radius_m'       => SummitTrackService::VERIFY_RADIUS_M,
            'summit_located' => $summit && is_numeric($summit->latitude) && is_numeric($summit->longitude),
        ];
    }

    public function get_track(Request $request, $ascent_id)
    {
        $ascent = $this->own_ascent($request, $ascent_id);
        if (!$ascent) return $this->error('not_found', 404);
        return response()->json($this->payload($ascent, $ascent->track));
    }

    public function upload_gpx(Request $request, $ascent_id)
    {
        $ascent = $this->own_ascent($request, $ascent_id);
        if (!$ascent || !$ascent->summit) return $this->error('not_found', 404);

        $request->validate(['file' => 'required|file|max:20480']);
        $file = $request->file('file');
        if (strtolower($file->getClientOriginalExtension()) !== 'gpx') return $this->error('not_gpx');

        try {
            $points = SummitTrackService::parse_gpx($file->getRealPath());
        } catch (RuntimeException $e) {
            return $this->error($e->getMessage());
        }
        $stats = SummitTrackService::analyse($points, $ascent->summit);

        return $this->save($ascent, $request, $stats, 'gpx', SummitTrackService::simplify($points), null);
    }

    public function import_strava(Request $request, $ascent_id)
    {
        $ascent = $this->own_ascent($request, $ascent_id);
        if (!$ascent || !$ascent->summit) return $this->error('not_found', 404);
        $request->validate(['activity_id' => 'required|integer|min:1']);

        $connection = StravaConnection::where('user_id', $request->user()->id)->first();
        if (!$connection) return $this->error('strava_not_connected');

        try {
            [$start, $streams] = StravaService::activity_streams($connection, (int) $request->activity_id);
            $points = SummitTrackService::from_strava_streams($streams, $start);
        } catch (RuntimeException $e) {
            return $this->error($e->getMessage(), $e->getMessage() === 'strava_rate_limit' ? 429 : 422);
        }
        $stats = SummitTrackService::analyse($points, $ascent->summit);

        return $this->save($ascent, $request, $stats, 'strava', null, (int) $request->activity_id);
    }

    public function delete_track(Request $request, $ascent_id)
    {
        $ascent = $this->own_ascent($request, $ascent_id);
        if (!$ascent) return $this->error('not_found', 404);
        SummitAscentTrack::where('ascent_id', $ascent->id)->delete();
        return response()->json(['success' => true]);
    }
}

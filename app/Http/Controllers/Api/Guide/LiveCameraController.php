<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\LiveCamera;
use App\Services\UbiaCameraService;

class LiveCameraController extends Controller
{
    function get_activ_live_camera(Request $request) {
        $camera = LiveCamera::where('id', '=', $request->id)->first();

        if ($camera && $camera->source === 'ubia' && $camera->ubia_device_uid) {
            $camera->display_photos = UbiaCameraService::getRecentPhotos($camera->ubia_device_uid);
        } elseif ($camera && $camera->source === 'upload') {
            $camera->display_photos = $camera->photos->map(fn ($photo) => [
                'captured_at' => $photo->created_at->toISOString(),
                'image_url'   => asset('images/live_cameras_img/' . $photo->image),
            ]);
        }

        return $camera;
    }

    function get_live_cameras(Request $request) {
        return LiveCamera::all();
    }

    // First published photo-bearing (ubia/upload) camera attached to an
    // article — used by the ice page's timeline. 'link'-source cameras have
    // no date-bucketed photo history, so they're not returned here.
    function get_camera_for_article(Request $request, $article_id) {
        $camera = LiveCamera::where('article_id', $article_id)
            ->where('published', true)
            ->whereIn('source', ['ubia', 'upload'])
            ->first(['id', 'name', 'source']);

        // Stays a plain 200 even when $camera is null — a 404 here trips the
        // app's global axios interceptor, which shows a visible error toast
        // for any unhandled 4xx/5xx status. "No public camera for this
        // article" is a completely normal state, not an error to surface.
        // response()->json(null) itself serializes as "{}" (truthy on the
        // frontend), so the frontend checks for `.id` presence instead of
        // relying on truthiness.
        return response()->json($camera);
    }

    // Buckets a camera's photos into one entry per day for the last N days
    // (default 7), oldest first, so the frontend can drive a day-by-day
    // timeline without doing its own date math.
    function get_camera_timeline(Request $request, $id) {
        $days   = max(1, (int) ($request->days ?? 7));
        $camera = LiveCamera::where('id', $id)->where('published', true)->first();

        if (!$camera) {
            return response()->json(['days' => []], 404);
        }

        $photos = [];

        if ($camera->source === 'ubia' && $camera->ubia_device_uid) {
            $photos = UbiaCameraService::getRecentPhotos($camera->ubia_device_uid, $days * 24);
        } elseif ($camera->source === 'upload') {
            $photos = $camera->photos()
                ->where('created_at', '>=', now()->subDays($days))
                ->get()
                ->map(fn ($photo) => [
                    'captured_at' => $photo->created_at->toISOString(),
                    'image_url'   => asset('images/live_cameras_img/' . $photo->image),
                ])
                ->values()
                ->all();
        }

        $byDate = [];
        foreach ($photos as $photo) {
            if (empty($photo['captured_at'])) continue;
            $date = \Carbon\Carbon::parse($photo['captured_at'])->format('Y-m-d');
            $byDate[$date][] = $photo;
        }

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $result[] = [
                'date'   => $date,
                'images' => $byDate[$date] ?? [],
            ];
        }

        return response()->json(['days' => $result, 'camera_name' => $camera->name]);
    }
}

<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\LiveCamera;
use App\Models\User\LiveCameraPhoto;
use App\Services\PermissionService;
use App\Services\UbiaCameraService;
use App\Services\Abstract\ImageControllService;
use Illuminate\Support\Facades\File;

class LiveCameraController extends Controller
{
    private const IMAGE_DIR = 'images/live_cameras_img/';

    // Lets the admin form confirm a UBIA device UID actually pulls photos
    // before saving, instead of finding out on the public page.
    function preview_ubia_camera(Request $request) {
        if ($auth = PermissionService::authorize('live_camera', 'show')) return $auth;

        $request->validate(['device_uid' => 'required|string']);

        return response()->json([
            'photos' => UbiaCameraService::getRecentPhotos($request->device_uid, 24 * 7),
        ]);
    }

    // function get_live_cameras(Request $request) {
    //     return LiveCamera::all();
    // }

    // Unified gallery for the camera detail view — works for both 'ubia'
    // (live-pulled from UBIA's cloud) and 'upload' (stored locally) sources,
    // with an optional look-back window so older photos can be browsed.
    // UBIA's own cloud plan only retains a 30-day rolling window server-side,
    // so `days` beyond that just returns whatever UBIA still has.
    function get_camera_gallery(Request $request) {
        if ($auth = PermissionService::authorize('live_camera', 'show')) return $auth;

        $request->validate([
            'id'   => 'required|integer|exists:live_cameras,id',
            'days' => 'nullable|integer|min:1',
        ]);

        $camera = LiveCamera::findOrFail($request->id);
        $days   = $request->days;

        if ($camera->source === 'ubia' && $camera->ubia_device_uid) {
            $photos = UbiaCameraService::getRecentPhotos($camera->ubia_device_uid, ($days ?: 90) * 24);
        } elseif ($camera->source === 'upload') {
            $photos = LiveCameraPhoto::where('live_camera_id', $camera->id)
                ->when($days, fn ($q) => $q->where('created_at', '>=', now()->subDays($days)))
                ->orderByDesc('created_at')
                ->get()
                ->map(fn ($photo) => [
                    'captured_at' => $photo->created_at->toISOString(),
                    'image_url'   => asset(self::IMAGE_DIR . $photo->image),
                    'id'          => $photo->id,
                ])
                ->values();
        } else {
            $photos = [];
        }

        return response()->json(['photos' => $photos]);
    }

    private function validatedLiveCameraData(Request $request): array {
        return $request->validate([
            'data.name'             => 'required|string|max:255',
            'data.source'           => 'required|in:link,ubia,upload',
            'data.link'             => 'required_if:data.source,link|nullable|string',
            'data.ubia_device_uid'  => 'required_if:data.source,ubia|nullable|string',
            'data.published'        => 'required|boolean',
            'data.article_id'       => 'required|integer|exists:articles,id',
        ])['data'];
    }

    function add_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'add');
        if ($auth) return $auth;

        $liveCamera = LiveCamera::create($this->validatedLiveCameraData($request));
        return response()->json($liveCamera, 201);
    }

    function edit_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;

        $liveCamera = LiveCamera::find($request->id);
        if (!$liveCamera) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $liveCamera->update($this->validatedLiveCameraData($request));
        return response()->json($liveCamera);
    }

    function get_editing_live_camera(Request $request) {
        if ($auth = PermissionService::authorize('live_camera', 'show')) return $auth;

        return LiveCamera::with('photos')->where('id', '=', $request->id)->first();
    }

    // Plain-array shape expected by the shared galleryImageEditComponent
    // (get_images_route_prop), same as ArticleGalleryController's equivalent.
    function get_editing_live_camera_photos(Request $request, $live_camera_id) {
        if ($auth = PermissionService::authorize('live_camera', 'show')) return $auth;

        return LiveCameraPhoto::where('live_camera_id', $live_camera_id)->orderByDesc('created_at')->get();
    }

    // Manually uploaded photos (e.g. pulled off the camera's SD card) for
    // cameras whose source is 'upload' — no vendor account/API involved.
    function upload_live_camera_photos(Request $request) {
        if ($auth = PermissionService::authorize('live_camera', 'edit')) return $auth;

        $request->validate([
            'live_camera_id' => 'required|integer|exists:live_cameras,id',
            'photos'         => 'required|array|min:1',
            'photos.*'       => 'image|max:8192',
        ]);

        $liveCamera = LiveCamera::findOrFail($request->live_camera_id);

        foreach ($request->file('photos') as $file) {
            $fileName = ImageControllService::upload_loop_image(self::IMAGE_DIR, $file, 2);

            LiveCameraPhoto::create([
                'live_camera_id' => $liveCamera->id,
                'image'          => $fileName,
            ]);
        }

        return response()->json($liveCamera->photos()->get());
    }

    function delete_live_camera_photo(Request $request, $id) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;

        $photo = LiveCameraPhoto::find($id);
        if (!$photo) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $file = public_path(self::IMAGE_DIR . $photo->image);
        if (file_exists($file)) {
            File::delete($file);
        }

        $photo->delete();

        return response()->json(['deleted' => true]);
    }

    // function get_activ_live_camera(Request $request) {
    //     return LiveCamera::where('id', '=', $request->id)->first();
    // }

    function del_live_camera(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'del');
        if ($auth) return $auth;

        // dd($request->id);
        $deleted = LiveCamera::where('id', $request->id)->delete();
        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }

    function bulk_delete(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'del');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        LiveCamera::destroy($request->ids);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function bulk_publish(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        LiveCamera::whereIn('id', $request->ids)->update(['published' => 1]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }

    function bulk_unpublish(Request $request) {
        $auth = PermissionService::authorize('live_camera', 'edit');
        if ($auth) return $auth;

        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer',
        ]);

        LiveCamera::whereIn('id', $request->ids)->update(['published' => 0]);

        return response()->json(['success' => true, 'count' => count($request->ids)]);
    }
}

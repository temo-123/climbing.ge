<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\SpotRocksImageExtraDrawing;
use App\Services\PermissionService;

// Same idea as SectorLocalImageExtraDrawingController, for the
// spot_rocks_image side — one general annotation layer shared by every
// sector that uses this image, unlike SpotRocksImageJson which deliberately
// varies per sector. Permission subject mirrors SpotRockController's own
// canvas-drawing endpoints ('sector'), not a dedicated 'spot_rock' subject.
class SpotRocksImageExtraDrawingController extends Controller
{
    public function get_for_editor($spot_rocks_image_id)
    {
        if ($auth = PermissionService::authorize('sector', 'show')) return $auth;

        $image = Spot_rocks_image::findOrFail($spot_rocks_image_id);
        $image->has_original = file_exists(public_path('images/spot_rocks_img/origin_img/' . $image->image));

        return [
            'image'         => $image,
            'extra_drawing' => SpotRocksImageExtraDrawing::where('spot_rocks_image_id', $spot_rocks_image_id)->first(),
        ];
    }

    public function save(Request $request, $spot_rocks_image_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $image = Spot_rocks_image::find($spot_rocks_image_id);
        if (!$image) {
            return response()->json(['error' => 'Spot rocks image not found'], 404);
        }

        $json = $request->input('json');
        if (!$json) {
            return response()->json(['error' => 'json is required'], 422);
        }

        $editedImageData = $request->edited_image; // base64 data URL, optional
        $canvasWidth = $request->canvas_width ? (int) $request->canvas_width : null;
        $canvasHeight = $request->canvas_height ? (int) $request->canvas_height : null;
        // The background photo's own actual position + size within that view
        // — see the migration/CanvasManager comments for why this can't be
        // assumed to be (0,0) + the full view size.
        $bgLeft = $request->bg_left;
        $bgTop = $request->bg_top;
        $bgWidth = $request->bg_width;
        $bgHeight = $request->bg_height;

        // Same composite pattern SpotRockController@save_drawing already uses
        // for this same spot_rocks_img folder.
        if ($editedImageData) {
            $filename = $image->image;
            $originalDir = public_path('images/spot_rocks_img/origin_img/');
            $editedPath = public_path('images/spot_rocks_img/' . $filename);
            $originalPath = $originalDir . $filename;

            if (!is_dir($originalDir)) {
                mkdir($originalDir, 0775, true);
            }
            if (!file_exists($originalPath) && file_exists($editedPath)) {
                copy($editedPath, $originalPath);
            }

            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $editedImageData);
            file_put_contents($editedPath, base64_decode($imageData));
            // Bump updated_at so frontends can cache-bust the <img> URL (same filename
            // gets overwritten every save) — without this, browsers keep serving the
            // stale pre-save bytes indefinitely since there's no other change signal.
            $image->touch();
        }

        $drawing = SpotRocksImageExtraDrawing::updateOrCreate(
            ['spot_rocks_image_id' => $spot_rocks_image_id],
            [
                'json' => $json,
                'canvas_width' => $canvasWidth,
                'canvas_height' => $canvasHeight,
                'bg_left' => $bgLeft,
                'bg_top' => $bgTop,
                'bg_width' => $bgWidth,
                'bg_height' => $bgHeight,
            ]
        );

        return response()->json(['success' => true, 'extra_drawing' => $drawing]);
    }

    public function delete($spot_rocks_image_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $deleted = SpotRocksImageExtraDrawing::where('spot_rocks_image_id', $spot_rocks_image_id)->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}

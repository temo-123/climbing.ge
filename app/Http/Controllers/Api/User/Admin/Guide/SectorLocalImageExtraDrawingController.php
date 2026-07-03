<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector_local_image;
use App\Models\Guide\SectorLocalImageExtraDrawing;
use App\Services\PermissionService;

// Same idea as SectorImageExtraDrawingController, for the sector_local_image
// ("local approach photo") side — one general annotation layer shared by
// every sector that uses this image, unlike SectorLocalImagesJson which
// deliberately varies per sector.
class SectorLocalImageExtraDrawingController extends Controller
{
    public function get_for_editor($sector_local_image_id)
    {
        if ($auth = PermissionService::authorize('sector_local_image', 'show')) return $auth;

        $image = Sector_local_image::findOrFail($sector_local_image_id);
        $image->has_original = file_exists(public_path('images/sector_local_img/origin_img/' . $image->image));

        return [
            'sector_local_image' => $image,
            'extra_drawing' => SectorLocalImageExtraDrawing::where('sector_local_image_id', $sector_local_image_id)->first(),
        ];
    }

    public function save(Request $request, $sector_local_image_id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'edit');
        if ($auth) return $auth;

        $image = Sector_local_image::find($sector_local_image_id);
        if (!$image) {
            return response()->json(['error' => 'Sector local image not found'], 404);
        }

        $json = $request->json;
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

        // Same composite pattern SectorLocalImagesController@save_canvas_data
        // already uses for this same sector_local_img folder.
        if ($editedImageData) {
            $filename = $image->image;
            $originalDir = public_path('images/sector_local_img/origin_img/');
            $editedPath = public_path('images/sector_local_img/' . $filename);
            $originalPath = $originalDir . $filename;

            if (!is_dir($originalDir)) {
                mkdir($originalDir, 0775, true);
            }
            if (!file_exists($originalPath) && file_exists($editedPath)) {
                copy($editedPath, $originalPath);
            }

            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $editedImageData);
            file_put_contents($editedPath, base64_decode($imageData));
        }

        $drawing = SectorLocalImageExtraDrawing::updateOrCreate(
            ['sector_local_image_id' => $sector_local_image_id],
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

    public function delete($sector_local_image_id)
    {
        $auth = PermissionService::authorize('sector_local_image', 'edit');
        if ($auth) return $auth;

        $deleted = SectorLocalImageExtraDrawing::where('sector_local_image_id', $sector_local_image_id)->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}

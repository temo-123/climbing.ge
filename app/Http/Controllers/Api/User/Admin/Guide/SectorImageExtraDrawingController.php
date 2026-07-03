<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector_image;
use App\Models\Guide\SectorImageExtraDrawing;
use App\Services\PermissionService;

// Admin CRUD for the general-purpose "extra info" drawing on a sector topo
// photo — same shape as RouteController@save_route_drawing (which this
// mirrors closely) but keyed only by sector_image_id, with no route_id and
// no sector_id, since this annotation isn't about any one route.
class SectorImageExtraDrawingController extends Controller
{
    public function get_for_editor($sector_image_id)
    {
        if ($auth = PermissionService::authorize('sector', 'show')) return $auth;

        $sectorImage = Sector_image::findOrFail($sector_image_id);
        $sectorImage->has_original = file_exists(public_path('images/sector_img/origin_img/' . $sectorImage->image));

        return [
            'sector_image'  => $sectorImage,
            'extra_drawing' => SectorImageExtraDrawing::where('sector_image_id', $sector_image_id)->first(),
        ];
    }

    public function save(Request $request, $sector_image_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $sectorImage = Sector_image::find($sector_image_id);
        if (!$sectorImage) {
            return response()->json(['error' => 'Sector image not found'], 404);
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

        // Same composite pattern RouteController@save_route_drawing already
        // uses for this same sector_images folder: back up the clean photo
        // once, then overwrite the live file with photo + drawn annotations
        // baked in, so a plain <img> of the sector image already shows them.
        if ($editedImageData) {
            $filename = $sectorImage->image;
            $originalDir = public_path('images/sector_img/origin_img/');
            $editedPath = public_path('images/sector_img/' . $filename);
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

        $drawing = SectorImageExtraDrawing::updateOrCreate(
            ['sector_image_id' => $sector_image_id],
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

    public function delete($sector_image_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $deleted = SectorImageExtraDrawing::where('sector_image_id', $sector_image_id)->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}

<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Spot_rocks_image;
use App\Models\Guide\SpotRocksImageJson;
use App\Models\Guide\Sector;

use App\Services\Abstract\ImageControllService;
use App\Services\CanvasService;
use App\Services\PermissionService;

class SpotRockController extends Controller
{
    public function del_spot_rock_image(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'del');
        if ($auth) return $auth;

        $image = Spot_rocks_image::where('id', '=', $request->image_id)->first();
        // Drawing data lives on this photo, one row per sector — clean it all
        // up before the photo itself goes, same as CanvasService does for the
        // other drawing tables.
        CanvasService::deleteSpotRocksImageCanvasData($image->id);
        ImageControllService::image_delete('images/spot_rocks_img/', $image, 'image');
        $image ->delete();
    }

    // ── Canvas drawing ─────────────────────────────────────────────────────────
    // Adds a route/approach-line drawing on top of an EXISTING spot_rocks_image
    // — no new image entity, the photo itself is still uploaded/managed via the
    // plain gallery form (SectorImagesFormComponent.vue). Real close to
    // SectorLocalImagesController: one photo, several linked entities, one
    // drawing layout per entity — except here the "linked entities" (this
    // photo's article's own Sectors) don't need a separate pivot to manage,
    // they're just Sector::where('article_id', $image->article_id).

    public function get_for_editor($id)
    {
        if ($auth = PermissionService::authorize('sector', 'show')) return $auth;

        $image = Spot_rocks_image::findOrFail($id);
        $image->has_original = file_exists(public_path('images/spot_rocks_img/origin_img/' . $image->image));

        return [
            'image'   => $image,
            'sectors' => Sector::where('article_id', $image->article_id)->orderBy('num')->get(),
            'layouts' => SpotRocksImageJson::where('spot_rocks_image_id', $id)
                            ->with('sector')
                            ->get(),
        ];
    }

    public function save_drawing(Request $request, $image_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $json            = $request->json;
        $sectorId        = $request->sector_id;
        $editedImageData = $request->edited_image;
        $canvasWidth     = $request->canvas_width  ? (int) $request->canvas_width  : null;
        $canvasHeight    = $request->canvas_height ? (int) $request->canvas_height : null;
        // The background photo's own actual position + size within that view —
        // see the migration/CanvasManager comments for why this can't be
        // assumed to be (0,0) + the full view size.
        $bgLeft   = $request->bg_left;
        $bgTop    = $request->bg_top;
        $bgWidth  = $request->bg_width;
        $bgHeight = $request->bg_height;

        if (!$sectorId) {
            return response()->json(['error' => 'sector_id is required'], 422);
        }

        $image    = Spot_rocks_image::findOrFail($image_id);
        $filename = $image->image;
        $hasOriginal = false;

        // Save composite image (background photo + all drawn strokes baked in)
        if ($filename && $editedImageData) {
            $originalDir  = public_path('images/spot_rocks_img/origin_img/');
            $editedPath   = public_path('images/spot_rocks_img/' . $filename);
            $originalPath = $originalDir . $filename;

            if (!is_dir($originalDir)) {
                mkdir($originalDir, 0775, true);
            }

            // Backup the clean original once — never overwrite it
            if (!file_exists($originalPath) && file_exists($editedPath)) {
                copy($editedPath, $originalPath);
            }

            // Overwrite main file with the composite (photo + strokes)
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $editedImageData);
            file_put_contents($editedPath, base64_decode($imageData));
            $hasOriginal = file_exists($originalPath);

            // The filename never changes on re-save, so bump updated_at — the
            // admin gallery cache-busts the <img> URL with it, otherwise the
            // browser keeps showing the stale pre-edit image it already cached.
            $image->touch();
        }

        // Upsert by (spot_rocks_image_id, sector_id) — guarantees one record
        // per sector, prevents duplicates regardless of what layoutId the
        // client sends.
        $jsonRecord = SpotRocksImageJson::updateOrCreate(
            [
                'spot_rocks_image_id' => $image_id,
                'sector_id'           => $sectorId,
            ],
            [
                'json'          => $json,
                'canvas_width'  => $canvasWidth,
                'canvas_height' => $canvasHeight,
                'bg_left'       => $bgLeft,
                'bg_top'        => $bgTop,
                'bg_width'      => $bgWidth,
                'bg_height'     => $bgHeight,
            ]
        );

        return response()->json([
            'success'      => true,
            'layout_id'    => $jsonRecord->id,
            'has_original' => $hasOriginal,
        ]);
    }

    public function del_layout($layout_id)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;

        $layout = SpotRocksImageJson::find($layout_id);
        if ($layout) $layout->delete();

        return response()->json(['success' => true]);
    }
}

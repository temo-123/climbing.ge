<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;
use App\Models\Guide\MtpPitchJson;
use App\Models\Guide\Sector_image;

use App\Services\PermissionService;

class MTPPitchController extends Controller
{
    public function get_mtp_pitchs_for_model(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'show');
        if ($auth) return $auth;

        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    }

    public function pitch_sequence(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;

        if($request->pitch_sequence){
            $pitch_num = 0;
            foreach ($request->pitch_sequence as $pitch) {
                $pitch_id = $pitch['id'];
                $pitch = Mtp_pitch::where('id',strip_tags($pitch_id))->first();
                $pitch_num++;
                $pitch['num'] = $pitch_num;
                $pitch->update();
            }
        }
    }

    public function mtp_pitch_edit(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;

        $pitch_validate = $this->pitch_validate($request->data);
        if ($pitch_validate != null) {
            return response()->json([
                $pitch_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                $article = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();

                $article['mtp_id']=$request->data['mtp_id'];
                $article['category']=$request->data['category'];
                $article['grade']=$request->data['grade'];
                $article['or_grade']=$request->data['or_grade'] ;
                $article['name']=$request->data['name'];
                $article['text_us']=$request->data['text_us'] ?? null;
                $article['text_ka']=$request->data['text_ka'] ?? null;
                $article['height']=$request->data['height'];
                $article['bolts']=$request->data['bolts'];

                $article['author'] = $request->data["author"];
                $article['creation_data'] = $request->data["creation_data"];
                $article['first_ascent'] = $request->data["first_ascent"];

                $article -> save();
            }
        }
    }

    public function mtp_pitch_add(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'add');
        if ($auth) return $auth;

        $pitch_validate = $this->pitch_validate($request->data);
        if ($pitch_validate != null) {
            return response()->json([
                $pitch_validate
            ], 422);
        }
        else{
            if ($request -> isMethod('post')) {
                $new_pitch = new Mtp_pitch();

                $sector_mtp_pitch_count = Mtp_pitch::where('mtp_id',strip_tags($request->data['mtp_id']))->count();
                if($sector_mtp_pitch_count == 0){
                    $new_pitch_num = 1;
                }
                else{
                    $new_pitch_num = $sector_mtp_pitch_count+1;
                }

                $new_pitch['mtp_id']=$request->data['mtp_id'];
                $new_pitch['num']=$new_pitch_num;
                $new_pitch['category']=$request->data['category'];
                $new_pitch['grade']=$request->data['grade'];
                $new_pitch['or_grade']=$request->data['or_grade'] ;
                $new_pitch['name']=$request->data['name'];
                $new_pitch['text_us']=$request->data['text_us'] ?? null;
                $new_pitch['text_ka']=$request->data['text_ka'] ?? null;
                $new_pitch['height']=$request->data['height'];
                $new_pitch['bolts']=$request->data['bolts'];

                $new_pitch['author'] = $request->data["author"];
                $new_pitch['creation_data'] = $request->data["creation_data"];
                $new_pitch['first_ascent'] = $request->data["first_ascent"];

                $new_pitch -> save();
            }
        }
    }

    public function del_pitch(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'del');
        if ($auth) return $auth;

        $sector = Mtp_pitch::where('id',strip_tags($request->pitch_id))->first();
        $sector ->delete();
    }

    public function get_editin_pitch(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;

        return Mtp_pitch::with('json')->where('id',strip_tags($request->pitch_id))->first();
    }

    public function pitchs_sequence(Request $request)
    {
        $pitch_num = 0;
        foreach ($request->pitchs_sequence as $pitch) {
            $pitch_id = $pitch['id'];
            $pitch = Mtp_pitch::where('id',strip_tags($pitch_id))->first();
            $pitch_num++;
            $pitch['num'] = $pitch_num;
            $pitch->update();
        }
    }

    // ── Canvas drawing ─────────────────────────────────────────────────────────

    public function save_pitch_drawing(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;

        $pitchId       = $request->pitch_id;
        $sectorImageId = $request->sector_image_id;
        $json          = $request->json;
        $editedImageData = $request->edited_image;
        // Paper.js view size + the background photo's own position/size within that
        // view at save time — needed so the editor can rescale saved strokes onto
        // the current background fit next time this pitch is reopened (mirrors
        // RouteController::save_route_drawing).
        $canvasWidth  = $request->canvas_width;
        $canvasHeight = $request->canvas_height;
        $bgLeft   = $request->bg_left;
        $bgTop    = $request->bg_top;
        $bgWidth  = $request->bg_width;
        $bgHeight = $request->bg_height;

        if (!$pitchId || !$sectorImageId || !$json) {
            return response()->json(['error' => 'pitch_id, sector_image_id and json are required'], 422);
        }

        $sectorImage = Sector_image::find($sectorImageId);
        if (!$sectorImage) {
            return response()->json(['error' => 'Sector image not found'], 404);
        }

        $filename     = $sectorImage->image;
        $originalDir  = public_path('images/sector_img/origin_img/');
        $editedPath   = public_path('images/sector_img/' . $filename);
        $originalPath = $originalDir . $filename;

        if (!is_dir($originalDir)) {
            mkdir($originalDir, 0775, true);
        }

        if (!file_exists($originalPath) && file_exists($editedPath)) {
            copy($editedPath, $originalPath);
        }

        if ($editedImageData) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $editedImageData);
            file_put_contents($editedPath, base64_decode($imageData));
        }

        $attrs = [
            'json' => $json,
            'sector_image_id' => $sectorImageId,
            'canvas_width' => $canvasWidth,
            'canvas_height' => $canvasHeight,
            'bg_left' => $bgLeft,
            'bg_top' => $bgTop,
            'bg_width' => $bgWidth,
            'bg_height' => $bgHeight,
        ];

        $existing = MtpPitchJson::where('mtp_pitch_id', $pitchId)->first();
        if ($existing) {
            $existing->update($attrs);
        } else {
            MtpPitchJson::create(array_merge(['mtp_pitch_id' => $pitchId], $attrs));
        }

        return response()->json(['success' => true, 'filename' => $filename]);
    }

    public function del_pitch_drawing(Request $request, $pitch_id)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'edit');
        if ($auth) return $auth;

        MtpPitchJson::where('mtp_pitch_id', $pitch_id)->delete();

        return response()->json(['success' => true]);
    }

    public function get_pitch_jsons_for_sector_image(Request $request)
    {
        $auth = PermissionService::authorize('mtp_pitch', 'show');
        if ($auth) return $auth;

        $sectorImageId  = strip_tags($request->sector_image_id);
        $excludePitchId = $request->has('exclude_pitch_id') ? strip_tags($request->exclude_pitch_id) : null;

        $query = MtpPitchJson::where('sector_image_id', $sectorImageId);
        if ($excludePitchId) {
            $query->where('mtp_pitch_id', '!=', $excludePitchId);
        }

        // Include canvas_width/canvas_height/bg_* alongside the json so the editor can
        // rescale each related pitch's strokes onto the current background fit.
        return $query->with('pitch')->get()->map(function($item) {
            return [
                'mtp_pitch_id' => $item->mtp_pitch_id,
                'json' => $item->json,
                'canvas_width' => $item->canvas_width,
                'canvas_height' => $item->canvas_height,
                'bg_left' => $item->bg_left,
                'bg_top' => $item->bg_top,
                'bg_width' => $item->bg_width,
                'bg_height' => $item->bg_height,
            ];
        });
    }

    public function pitch_validate($request)
    {
        $validator = Validator::make($request, [
            'name' => 'required|max:190',
            'grade' => 'required',
            'mtp_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
}

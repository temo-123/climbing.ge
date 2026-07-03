<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;

use App\Models\Guide\SectorLocalImageExtraDrawing;

class SectorLocalImageExtraDrawingController extends Controller
{
    public function get($sector_local_image_id)
    {
        $drawing = SectorLocalImageExtraDrawing::where('sector_local_image_id', $sector_local_image_id)->first();

        return response()->json(['extra_drawing' => $drawing]);
    }
}

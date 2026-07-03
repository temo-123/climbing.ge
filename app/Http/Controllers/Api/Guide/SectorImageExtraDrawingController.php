<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;

use App\Models\Guide\SectorImageExtraDrawing;

class SectorImageExtraDrawingController extends Controller
{
    public function get($sector_image_id)
    {
        $drawing = SectorImageExtraDrawing::where('sector_image_id', $sector_image_id)->first();

        return response()->json(['extra_drawing' => $drawing]);
    }
}

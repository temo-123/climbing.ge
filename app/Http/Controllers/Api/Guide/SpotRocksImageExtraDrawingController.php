<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;

use App\Models\Guide\SpotRocksImageExtraDrawing;

class SpotRocksImageExtraDrawingController extends Controller
{
    public function get($spot_rocks_image_id)
    {
        $drawing = SpotRocksImageExtraDrawing::where('spot_rocks_image_id', $spot_rocks_image_id)->first();

        return response()->json(['extra_drawing' => $drawing]);
    }
}

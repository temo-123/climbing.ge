<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Spot_rocks_image;

use App\Services\Abstract\ImageControllService;

class SpotRockController extends Controller
{
    public function get_spot_rock_images(Request $request)
    {
        return Spot_rocks_image::where('article_id', '=', $request->article_id)->get();
    }

    public function del_spot_rock_image(Request $request)
    {   
        $image = Spot_rocks_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/spot_rocks_img/', $image, 'image');
        $image ->delete();
    }
}

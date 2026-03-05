<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector_image;

class SectorImagesController extends Controller
{
    public function index()
    {
        return Sector_image::latest('id')->get();
    }

    public function get_sector_image(Request $request)
    {
        $sector_images = Sector_image::where('sector_id',"=", $request->sector_id)->orderBy('num')->get();
        $sector_images_count = Sector_image::where('sector_id',"=", $request->sector_id)->orderBy('num')->count();

        $sector_images_size = 100;
        if($sector_images_count > 1){
            $sector_images_size = 100 / $sector_images_count;
            $sector_images_size = $sector_images_size - 1;
        }

        return(
            $data = [
                "sector_images" => $sector_images,
                "sector_images_size" => $sector_images_size,
            ]
        );
    }

    public static function get_region_image(Request $request){
        $region_image = Article::where('id',strip_tags($request->region_id))->get('climbing_area_image');
        return $region_image;
    }
}

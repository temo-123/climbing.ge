<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Abstract\ImageControllService;

use App\Models\Guide\Sector_local_image;
use App\Models\Guide\Sector_local_image_sector;
use App\Models\Guide\SectorLocalImagesJson;
use App\Models\Guide\SectorLocalImagesJsonSector;
use App\Models\Guide\Sector;

class SectorLocalImagesController extends Controller
{
    public function get_all_sector_local_images()
    {
        return Sector_local_image::latest('id')->get();
    }

    public function get_sector_local_img_for_modal($id)
    {
        $sector_local_image = Sector_local_image::where("id", "=", $id)->first();

        $data = [
            "image" => $sector_local_image,
            "sectors" => $sector_local_image->sectors,
            "related_jsons" => $sector_local_image->jsons
        ];

        return $data;
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }
    
    public function get_layout($layout_id)
    {
        $layout = SectorLocalImagesJson::with('sectors')->find($layout_id);

        return response()->json(['layout' => $layout]);
    }

    public function get_layouts($sector_local_image_id)
    {
        $layouts = SectorLocalImagesJson::where('sector_local_image_id', $sector_local_image_id)->with('sectors')->get();

        return response()->json(['layouts' => $layouts]);
    }

    public function get_layout_old($sector_id)
    {
        $sector = Sector::find($sector_id);
        $jsons = $sector->sector_local_images_jsons;

        return response()->json(['jsons' => $jsons]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ice_sector_image;
use App\Services\ImageControllService;

class IceSectorImagesController extends Controller
{
    // public function index()
    // {
    //     return Ice_sector_image::latest('id')->get();
    // }
    
    public function get_sector_images(Request $request)
    {
        $ice_sector_images = Ice_sector_image::where("ice_sector_id", '=', $request->sector_id)->get();
        return $ice_sector_images;
    }
    
    public function del_sector_images($id)
    {
        $del_ice_img = Ice_sector_image::where("id", "=", $id)->first();
        ImageControllService::image_delete('images/ice_sector_img/', $del_ice_img, 'image');
        $del_ice_img -> delete();
    }
}

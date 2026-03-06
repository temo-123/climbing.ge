<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector_image;
use App\Services\PermissionService;

class SectorImagesController extends Controller
{
    public function save_sector_images_sequence(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        
        $image_num = 0;
        $images_sequence = $request->sector_images_sequence;
        foreach ($images_sequence as $image_sequence) {
            $image_sequence_id = $image_sequence['id'];
            $image = Sector_image::where('id',strip_tags($image_sequence_id))->first();
            // var_dump($image);
            // dd($image_sequence_id);
            $image_num++;
            // http_response_code(500);
            // dd('$image_num');
            $image['num'] = $image_num;
            $image->update();
        }
    }

    public function get_sector_editing_data(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'edit');
        if ($auth) return $auth;
        
        $sector = Sector::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "sector" => $sector,
            ]
        );
    }

    public function sector_image_upload(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'add');
        if ($auth) return $auth;
        
        $request->user()->authorizeRoles(['manager', 'admin']);

        $this->sector_image_validate($request);

        $sector = new Sector_image();
        $sector['sector_id']=$request->sector_id; 

        $file_new_name = ImageControllService::image_upload('images/sector_img/', $request, 'profile_pic', 0);

        $sector['image'] = $file_new_name;
        $sector -> save();
    }

    public function sector_image_delete(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'del');
        if ($auth) return $auth;
        
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $sector_image = Sector_image::where('id',strip_tags($request->id))->first();

            ImageControllService::image_delete('images/sector_img/', $sector_image, 'image');

            $sector_image -> delete();
        }
    }
}

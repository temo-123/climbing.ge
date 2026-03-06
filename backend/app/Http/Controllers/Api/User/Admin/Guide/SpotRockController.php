<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Spot_rocks_image;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

class SpotRockController extends Controller
{
    public function del_spot_rock_image(Request $request)
    {
        $auth = PermissionService::authorize('sector', 'del');
        if ($auth) return $auth;
        
        $image = Spot_rocks_image::where('id', '=', $request->image_id)->first();
        ImageControllService::image_delete('images/spot_rocks_img/', $image, 'image');
        $image ->delete();
    }
}

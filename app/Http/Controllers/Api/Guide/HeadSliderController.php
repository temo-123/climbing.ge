<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Services\Abstract\ImageControllService;

use App\Models\Guide\Header_image;

class HeadSliderController extends Controller
{
    public function get_slides (Request $request){
        return Header_image::where('published', '=', 1)->where('category', '=', $request->slide_category)->get();
    }

    public function get_all_slides (){
        return $data = [
            'guide_slides' => Header_image::where('category', '=', 'guide')->get(),
            'shop_slides' => Header_image::where('category', '=', 'shop')->get(),
        ];
    }

    public function get_actyve_slide (Request $request){
        return Header_image::where('id', '=', $request->slide_id)->first();
    }

}

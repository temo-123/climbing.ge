<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Store_slide;

class StoreSlideController extends Controller
{
    public function get_slides(){
        return Store_slide::where('published', '=', 1)->get();
    }

    public function get_all_slides(){
        return Store_slide::get();
    }

    public function get_editing_slides(Request $request){
        return Store_slide::where('id', '=', $request->slide_id)->get();
    }


    public function add_slide(){

    }

    public function edit_slide(){

    }

    public function del_slide(){

    }

}

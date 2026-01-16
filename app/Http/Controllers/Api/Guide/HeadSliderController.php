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

    // public function add_slide (Request $request) {
    //     $validation_issets;

    //     $data = json_decode($request->data, true );
        
    //     $image_validate = $this->gallery_image_add_validate($data, $request);
    //     if ($image_validate != null) {
    //         $validation_issets['form_data_validation'] = $image_validate;
    //     }
    //     else{
    //         $validation_issets['form_data_validation'] = false;
    //     }

    //     if (!$validation_issets['form_data_validation'] ) {
    //         $new_gallery_image = new Header_image;
        
    //         $new_gallery_image['title']=$data["title"];
    //         $new_gallery_image['text']=$data["text"];
    //         $new_gallery_image['published']=$data["published"];
    //         $new_gallery_image['category']=$data["category"];
    //         $new_gallery_image['link']=$data["link"];

    //         if($request->hasFile('image')){
    //             $new_gallery_image['image'] =  ImageControllService::image_upload('images/head_slider_img/'.$data['category'].'/', $request, 'image', 1);
    //         }
        
    //         $new_gallery_image->save();
    //     }
    //     else{
    //         return response()->json([
    //             $image_validate,
    //         ], 422);
    //     }
    // }

    // public function edit_slide (Request $request) {
    //     if ($request->isMethod('post')) {
    //         $validation_issets;

    //         $data = json_decode($request->data, true );
            
    //         $image_validate = $this->gallery_image_edit_validate($data, $request);
    //         if ($image_validate != null) {
    //             $validation_issets['form_data_validation'] = $image_validate;
    //         }
    //         else{
    //             $validation_issets['form_data_validation'] = false;
    //         }

    //         if (!$validation_issets['form_data_validation'] ) {
    //             $edit_gallery_image = Header_image::where('id', '=', $request->slide_id)->first();
            
    //             $edit_gallery_image['title']=$data["title"];
    //             $edit_gallery_image['text']=$data["text"];
    //             $edit_gallery_image['published']=$data["published"];
    //             $edit_gallery_image['link']=$data["link"];
                
    //             if($request->hasFile('image')){
    //                 $edit_gallery_image['image'] =  ImageControllService::image_update('images/head_slider_img/'.$data['category'].'/', $edit_gallery_image, $request, 'image', 'image', 1);
    //             }
            
    //             $edit_gallery_image->save();
    //         }
    //         else{
    //             return response()->json([
    //                 $image_validate,
    //             ], 422);
    //         }
    //     }
    // }

    // public function del_slide (Request $request) 
    // {   
    //     $gallery = Header_image::where('id',strip_tags($request->slide_id))->first();

    //     // delete article file
    //     ImageControllService::image_delete('images/head_slider_img/'.$gallery->category.'/', $gallery, 'image');

    //     // delete product from db
    //     $gallery -> delete();
    // }


    // public function gallery_image_edit_validate($data, $request)
    // {
    //     $validator = Validator::make($data, [
    //         'published' => 'required',
    //         'title' => 'required|max:35',
    //         'text' => 'required|max:225',
    //     ]);
    //     if ($validator->fails()) {
    //         return $validator->messages();
    //     }

    //     $request->validate(
    //         [
    //             'image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    //         ],
    //         [
    //             'image' => 'Your image is uncorect',
    //             'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
    //             'image.mimes' => 'Your image need by in jpg, png, jpeg, gif or svg format',
    //             'image.image' => 'Your file is not a image'
    //         ]
    //     );
    // }
    // public function gallery_image_add_validate($data, $request)
    // {
    //     $validator = Validator::make($data, [
    //         'published' => 'required',
    //         'category' => 'required',
    //         'title' => 'required|max:35',
    //     ]);
    //     if ($validator->fails()) {
    //         return $validator->messages();
    //     }

    //     $request->validate(
    //         [
    //             'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
    //         ],
    //         [
    //             'image' => 'Your image is uncorect',
    //             'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
    //             'image.mimes' => 'Your image need by in jpg, png, jpeg, gif or svg format',
    //             'image.image' => 'Your file is not a image'
    //         ]
    //     );
    // }
}

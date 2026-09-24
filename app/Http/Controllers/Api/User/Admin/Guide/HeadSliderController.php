<?php

namespace App\Http\Controllers\Api\User\Admin\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Illuminate\Support\Facades\DB;

use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;

use App\Models\Guide\Header_image;

class HeadSliderController extends Controller
{
    public function get_editing_slide(Request $request)
    {
        if ($auth = PermissionService::authorize('head_slider', 'show')) return $auth;
        return Header_image::where('id', '=', $request->slide_id)->first();
    }

    public function add_slide (Request $request) {
        $auth = PermissionService::authorize('head_slider', 'add');
        if ($auth) return $auth;
        
        $validation_issets;

        $data = json_decode($request->data, true );
        
        $image_validate = $this->gallery_image_add_validate($data, $request);
        if ($image_validate != null) {
            $validation_issets['form_data_validation'] = $image_validate;
        }
        else{
            $validation_issets['form_data_validation'] = false;
        }

        if (!$validation_issets['form_data_validation'] ) {
            $new_gallery_image = new Header_image;
        
            $new_gallery_image['title']=$data["title"];
            $new_gallery_image['text']=$data["text"];
            $new_gallery_image['published']=$data["published"];
            $new_gallery_image['category']=$data["category"];
            $new_gallery_image['link']=$data["link"];
            $new_gallery_image['text_position']=$data["text_position"] ?? 'center';
            // New slides go to the end of their own category's sequence
            $new_gallery_image['sort_order'] = Header_image::where('category', '=', $data['category'])->max('sort_order') + 1;

            if($request->hasFile('image')){
                $new_gallery_image['image'] =  ImageControllService::image_upload('images/head_slider_img/'.$data['category'].'/', $request, 'image', 1);
            }
        
            $new_gallery_image->save();
        }
        else{
            return response()->json([
                $image_validate,
            ], 422);
        }
    }

    public function edit_slide (Request $request) {
        $auth = PermissionService::authorize('head_slider', 'edit');
        if ($auth) return $auth;
        
        if ($request->isMethod('post')) {
            $validation_issets;

            $data = json_decode($request->data, true );
            
            $image_validate = $this->gallery_image_edit_validate($data, $request);
            if ($image_validate != null) {
                $validation_issets['form_data_validation'] = $image_validate;
            }
            else{
                $validation_issets['form_data_validation'] = false;
            }

            if (!$validation_issets['form_data_validation'] ) {
                $edit_gallery_image = Header_image::where('id', '=', $request->slide_id)->first();
            
                $edit_gallery_image['title']=$data["title"];
                $edit_gallery_image['text']=$data["text"];
                $edit_gallery_image['published']=$data["published"];
                $edit_gallery_image['link']=$data["link"];
                $edit_gallery_image['text_position']=$data["text_position"] ?? 'center';
                
                if($request->hasFile('image')){
                    $edit_gallery_image['image'] =  ImageControllService::image_update('images/head_slider_img/'.$data['category'].'/', $edit_gallery_image, $request, 'image', 'image', 1);
                }

                $edit_gallery_image->save();
            }
            else{
                return response()->json([
                    $image_validate,
                ], 422);
            }
        }
    }

    /**
     * Save a new slide sequence for one category.
     * Body: { category: 'guide'|'shop', ids: [slide ids in the new order] }
     */
    public function reorder_slides (Request $request)
    {
        $auth = PermissionService::authorize('head_slider', 'edit');
        if ($auth) return $auth;

        $validator = Validator::make($request->all(), [
            'category' => 'required|string|in:guide,shop',
            'ids' => 'required|array',
            'ids.*' => 'integer|distinct',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->messages(), 422);
        }

        $ids = array_map('intval', $request->ids);

        // The list must be exactly this category's slides — no foreign or missing ids
        $category_ids = Header_image::where('category', '=', $request->category)->pluck('id')->all();
        sort($category_ids);
        $sorted_ids = $ids;
        sort($sorted_ids);
        if ($sorted_ids !== $category_ids) {
            return response()->json(['ids' => ['Slide list does not match this category, refresh and try again.']], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids as $index => $id) {
                Header_image::where('id', '=', $id)->update(['sort_order' => $index + 1]);
            }
        });

        return response()->json(['success' => true]);
    }

    public function del_slide (Request $request) 
    {
        $auth = PermissionService::authorize('head_slider', 'del');
        if ($auth) return $auth;
        
        $gallery = Header_image::where('id',strip_tags($request->slide_id))->first();

        // delete article file
        ImageControllService::image_delete('images/head_slider_img/'.$gallery->category.'/', $gallery, 'image');

        // delete product from db
        $gallery -> delete();
    }


    public function gallery_image_edit_validate($data, $request)
    {
        $validator = Validator::make($data, [
            'published' => 'required',
            'title' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }

        $imageValidator = Validator::make(
            ['image' => $request->file('image')],
            ['image' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048'],
            [
                'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
                'image.mimes' => 'Your image need by in jpg, png, jpeg, gif, svg or webp format',
                'image.image' => 'Your file is not a image',
            ]
        );
        if ($imageValidator->fails()) {
            return $imageValidator->messages();
        }
    }

    public function gallery_image_add_validate($data, $request)
    {
        $validator = Validator::make($data, [
            'published' => 'required',
            'category' => 'required',
            'title' => 'required|max:255',
        ]);
        if ($validator->fails()) {
            return $validator->messages();
        }

        $imageValidator = Validator::make(
            ['image' => $request->file('image')],
            ['image' => 'required|image|mimes:jpg,png,jpeg,gif,svg,webp|max:2048'],
            [
                'image.max' => 'Your image is wery big. (Max size = 2048Kb)',
                'image.mimes' => 'Your image need by in jpg, png, jpeg, gif, svg or webp format',
                'image.image' => 'Your file is not a image',
            ]
        );
        if ($imageValidator->fails()) {
            return $imageValidator->messages();
        }
    }
}

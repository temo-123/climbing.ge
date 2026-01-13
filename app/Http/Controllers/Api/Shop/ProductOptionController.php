<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;

use App\Services\GetProductsService;
use App\Services\GetProductService;
use App\Services\URLTitleService;
use App\Services\Abstract\ImageControllService;
use App\Services\GalleryService;

use App\Models\Shop\Product;
use App\Models\Shop\Product_option;
use App\Models\Shop\Option_image;


// use Storage;
// use Carbon\Carbon;
// use App\Models\Article;

// use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ProductOptionController extends Controller
{
    public function get_activ_product_options(Request $request)
    {
        $product = Product::where('id', '=', $request->product_id)->first();
        $data = [
            'product' => $product,
            'options' => $product->product_options,
            'category' => $product->product_category,
            'us_product' => $product->us_product,
        ];
        return $data;
    }

    // public function add_option(Request $request)
    // {
    //     $data = json_decode($request->data, true);
        
    //     $data_validation = $this->option_validation($data);

    //     if($data_validation != null){ 
    //         return response()->json([
    //             $data_validation
    //         ], 422);
    //     }
    //     else{
    //         $add_option = new Product_option;
    //         $add_option['name'] = $data['name'];
    //         $add_option['price'] = $data['price'];
    //         $add_option['currency'] = $data['currency'];
    //         $add_option['discount'] = $data['discount'] ?? 0;
    //         // $add_option['quantity'] = $data['quantity'];
    //         $add_option['product_id'] = $request['product_id'];
    //         $add_option->save();

    //         if($request->hasFile('images')){
    //             // $this->add_images($request->images, $add_option->id);
    //                 GalleryService::add_gallery_images(
    //                     $request->images, 
    //                     $add_option->id, 
    //                     Option_image::class, 
    //                     'image', 
    //                     'option_id', 
    //                     '/images/product_option_img/'
    //                 );
                
    //         }
    //     }
    // }

    // public function add_images($images, $option_id)
    // {
    //     foreach ($images as $image) {
    //         $file_new_name = ImageControllService::upload_loop_image('images/product_option_img/', $image, 1);

    //         if(file_exists(public_path('images/product_option_img/') . $file_new_name)){
    //             $new_option_image = new Option_image;
        
    //             $new_option_image['image'] = $file_new_name;
    //             $new_option_image['option_id'] = $option_id;
        
    //             $saiving = $new_option_image -> save();

    //             if($saiving){
    //                 echo 'Upload socsesful \n';
    //             }
    //         }
    //         else{
    //             echo 'Upload error \n';
    //         }
    //     }
    // }

    // public function get_editing_option(Request $request)
    // {
    //     $option = Product_option::where('id', '=', $request->option_id)->first();
    //     $data = [
    //         'option' => $option,
    //         'option_images' => $option->images,
    //     ];
    //     // dd($data);
    //     return $data;
    // }

    // public function edit_option(Request $request)
    // {
    //     $data = json_decode($request->data, true);
        
    //     $data_validation = $this->option_validation($data);

    //     if($data_validation != null){   
    //         return response()->json([
    //             $data_validation
    //         ], 422);
    //     }
    //     else{
    //         $edit_option = Product_option::where('id', '=', $request->option_id)->first();
    //         $edit_option['name'] = $data['name'];
    //         $edit_option['price'] = $data['price'];
    //         $edit_option['currency'] = $data['currency'];
    //         $edit_option['discount'] = $data['discount'] ?? 0;
    //         // $edit_option['quantity'] = $data['quantity'];
    //         $edit_option->save();

    //         if($request->hasFile('images')){
    //             // $this->add_images($request->images, $edit_option->id);
    //             GalleryService::add_gallery_images(
    //                 $request->images, 
    //                 $edit_option->id, 
    //                 Option_image::class, 
    //                 'image', 
    //                 'option_id', 
    //                 '/images/product_option_img/'
    //             );
    //         }
    //     }
    // }

    // public function del_option(Request $request)
    // {
    //     $option = Product_option::where('id', '=', $request->option_id)->first();
    //     $option_images_count = Option_image::where('option_id', '=', $option->id)->count();

    //     if($option_images_count > 0){
    //         $option_images = Option_image::where('option_id', '=', $option->id)->get();
    //         // dd($option_images);
    //         foreach ($option_images as $image) {
    //             ImageControllService::image_delete('images/product_option_img/', $image, 'image');
    //             $image ->delete();
    //         }
    //     }
    //     $option ->delete();
    // }

    // public function del_option_image(Request $request)
    // {
    //     $option_image = Option_image::where('id', '=', $request->image_id)->first();
    //     if($option_image){
    //         ImageControllService::image_delete('images/product_option_img/', $option_image, 'image');
    //         $option_image ->delete();
    //     }
    // }

    // public function option_validation($data)
    // {
    //     $validator = Validator::make($data, [
    //         'name' => 'required',
    //         'price' => 'required | numeric',
    //         'currency' => 'required',
    //         'discount' => 'nullable | numeric | min:0 | max:100',
    //         // 'quantity' => 'required',
    //     ]);
    //     if ($validator->fails()) {
    //         return $validator->messages();
    //     }
    // }
}
<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GalleryService;
use App\Services\Abstract\ImageControllService;
use App\Services\PermissionService;
use App\Models\Shop\Product_image;

class ProductImageController extends Controller
{
    public function get_images($product_id)
    {
        if ($auth = PermissionService::authorize('product', 'show')) return $auth;
        return Product_image::where('product_id', $product_id)->get();
    }

    public function add_images(Request $request, $product_id)
    {
        if ($auth = PermissionService::authorize('product', 'edit')) return $auth;
        if ($request->hasFile('images')) {
            GalleryService::add_gallery_images(
                $request->images,
                $product_id,
                Product_image::class,
                'image',
                'product_id',
                '/images/product_img/'
            );
        }
        return response()->json(['success' => true]);
    }

    public function del_image($image_id)
    {
        if ($auth = PermissionService::authorize('product', 'edit')) return $auth;
        $image = Product_image::where('id', $image_id)->first();
        if ($image) {
            ImageControllService::image_delete('images/product_img/', $image, 'image');
            $image->delete();
        }
        return response()->json(['success' => true]);
    }
}

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
}
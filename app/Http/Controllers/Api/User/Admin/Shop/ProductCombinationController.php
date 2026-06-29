<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\GalleryService;
use App\Services\PermissionService;
use App\Services\Abstract\ImageControllService;

use App\Models\Shop\ProductOptionCombination;
use App\Models\Shop\CombinateProductOptionImage;
use App\Models\Shop\Product_option;

use Intervention\Image\ImageManagerStatic as Image;

class ProductCombinationController extends Controller
{
    public function get_combinations($product_id)
    {
        if ($auth = PermissionService::authorize('product_option', 'show')) return $auth;

        $combinations = ProductOptionCombination::where('product_id', $product_id)
            ->with(['options.warehouse', 'images'])
            ->get()
            ->map(function ($combo) {
                $stock = $this->get_combination_stock($combo);
                return [
                    'combination'   => $combo,
                    'images'        => $combo->images,
                    'selected_options' => $combo->options->map(function ($opt) {
                        return [
                            'id'         => $opt->id,
                            'name'       => $opt->name,
                            'price'      => $opt->price,
                            'currency'   => $opt->currency,
                            'product_id' => $opt->product_id,
                        ];
                    }),
                    'stock_quantity'  => $stock,
                    'is_out_of_stock' => $stock <= 0,
                ];
            });

        return response()->json(['combinations' => $combinations]);
    }

    public function get_editing_combination($id)
    {
        if ($auth = PermissionService::authorize('product_option', 'show')) return $auth;

        $combo = ProductOptionCombination::with(['options', 'images'])->find($id);
        if (!$combo) return response()->json(['error' => 'Not found'], 404);

        return response()->json([
            'combination' => $combo,
            'combination_images' => $combo->images,
            'selected_options' => $combo->options->map(function ($opt) {
                return [
                    'id'         => $opt->id,
                    'name'       => $opt->name,
                    'price'      => $opt->price,
                    'currency'   => $opt->currency,
                    'product_id' => $opt->product_id,
                ];
            }),
        ]);
    }

    public function search_products(Request $request)
    {
        if ($auth = PermissionService::authorize('product_option', 'show')) return $auth;

        $query = $request->query('query', '');

        $products = \App\Models\Shop\Product::with('us_product')
            ->when($query, function ($q) use ($query) {
                $q->whereHas('us_product', function ($sq) use ($query) {
                    $sq->where('title', 'like', '%' . $query . '%');
                });
            })
            ->limit(40)
            ->get()
            ->map(function ($p) {
                return [
                    'id'    => $p->id,
                    'title' => optional($p->us_product)->title ?? 'Product #' . $p->id,
                ];
            });

        return response()->json(['products' => $products]);
    }

    public function get_product_options_for_combination($product_id)
    {
        if ($auth = PermissionService::authorize('product_option', 'show')) return $auth;

        $options = Product_option::where('product_id', $product_id)->get()->map(function ($opt) {
            return [
                'id'       => $opt->id,
                'name'     => $opt->name,
                'price'    => $opt->price,
                'currency' => $opt->currency,
                'product_id' => $opt->product_id,
            ];
        });

        return response()->json(['options' => $options]);
    }

    public function add_combination(Request $request)
    {
        if ($auth = PermissionService::authorize('product_option', 'add')) return $auth;

        $data = json_decode($request->data, true);
        $validation = $this->validate_combination($data);
        if ($validation) return response()->json([$validation], 422);

        $combo = new ProductOptionCombination;
        $combo->product_id = $request->product_id;
        $combo->name       = $data['name'];
        $combo->price      = $data['price'];
        $combo->currency   = $data['currency'];
        $combo->discount   = (int)($data['discount'] ?? 0);
        $combo->barcode    = !empty($data['barcode']) ? $data['barcode'] : null;
        $combo->save();

        if (!empty($data['option_ids']) && is_array($data['option_ids'])) {
            $combo->options()->sync($data['option_ids']);
        }

        if ($request->hasFile('images')) {
            GalleryService::add_gallery_images(
                $request->images,
                $combo->id,
                CombinateProductOptionImage::class,
                'image',
                'combination_id',
                '/images/product_option_img/'
            );
        }

        return response()->json(['success' => true, 'id' => $combo->id]);
    }

    public function edit_combination(Request $request, $id)
    {
        if ($auth = PermissionService::authorize('product_option', 'edit')) return $auth;

        $data = json_decode($request->data, true);
        $validation = $this->validate_combination($data);
        if ($validation) return response()->json([$validation], 422);

        $combo = ProductOptionCombination::find($id);
        if (!$combo) return response()->json(['error' => 'Not found'], 404);

        $combo->name     = $data['name'];
        $combo->price    = $data['price'];
        $combo->currency = $data['currency'];
        $combo->discount = (int)($data['discount'] ?? 0);
        $combo->barcode  = !empty($data['barcode']) ? $data['barcode'] : null;
        $combo->save();

        if (isset($data['option_ids']) && is_array($data['option_ids'])) {
            $combo->options()->sync($data['option_ids']);
        }

        if ($request->hasFile('images')) {
            GalleryService::add_gallery_images(
                $request->images,
                $combo->id,
                CombinateProductOptionImage::class,
                'image',
                'combination_id',
                '/images/product_option_img/'
            );
        }

        return response()->json(['success' => true]);
    }

    public function del_combination($id)
    {
        if ($auth = PermissionService::authorize('product_option', 'del')) return $auth;

        $combo = ProductOptionCombination::with('images')->find($id);
        if (!$combo) return response()->json(['error' => 'Not found'], 404);

        foreach ($combo->images as $image) {
            ImageControllService::image_delete('images/product_option_img/', $image, 'image');
            $image->delete();
        }

        $combo->options()->detach();
        $combo->delete();

        return response()->json(['success' => true]);
    }

    public function del_combination_image($image_id)
    {
        if ($auth = PermissionService::authorize('product_option', 'edit')) return $auth;

        $image = CombinateProductOptionImage::find($image_id);
        if ($image) {
            ImageControllService::image_delete('images/product_option_img/', $image, 'image');
            $image->delete();
        }

        return response()->json(['success' => true]);
    }

    private function get_combination_stock(ProductOptionCombination $combo): int
    {
        $options = $combo->options;
        if ($options->isEmpty()) return 0;

        $min = PHP_INT_MAX;
        foreach ($options as $option) {
            $general = $option->warehouse->where('general', 1)->first();
            $qty = $general ? (int)($general->pivot->quantity ?? 0) : 0;
            if ($qty < $min) $min = $qty;
        }

        return $min === PHP_INT_MAX ? 0 : $min;
    }

    private function validate_combination($data)
    {
        $validator = \Validator::make($data ?? [], [
            'name'     => 'required',
            'price'    => 'required|numeric',
            'currency' => 'required',
            'discount' => 'nullable|numeric|min:0|max:100',
            'barcode'  => 'nullable|string|max:255',
        ]);

        return $validator->fails() ? $validator->messages() : null;
    }
}

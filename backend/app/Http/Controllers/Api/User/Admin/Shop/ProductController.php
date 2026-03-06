<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;
use Auth;

use App\Services\ProductService;
use App\Services\URLTitleService;
use App\Services\PermissionService;

use App\Models\Shop\Product;
use App\Models\Shop\Locale_product;
use App\Models\Shop\Favorite_product;
use App\Models\Shop\Cart;
use App\Models\Shop\Product_option;
use App\Models\Shop\Option_image;
use App\Models\Shop\Product_category;
use App\Models\User\User_product;
use App\Models\User;

class ProductController extends Controller
{
    public function get_quick_product(Request $request)
    {
        $global_product = Product::where('id', '=', $request->product_id)->get();
        return ProductService::get_locale_product_use_locale($global_product, $request->lang);
    }


    public function add_product(Request $request)
    {
        $auth = PermissionService::authorize('product_category', 'add');
        // if ($auth) return $auth;
        $data = json_decode($request->data, true );
        
        $validator = Validator::make($data['global_product'], [
            'subcategory_id' => 'required',
            'brand_id' => 'required',
            'sale_type' => 'required|in:custom_production,online_order',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }
    
        // $product_adding = ProductService::add_content($data, product::class, Locale_product::class, '_product', $request);
        $product_adding = ProductService::add_content($data, Product::class, Locale_product::class, '_product', $request);
        
        if (!array_key_exists('validation', $product_adding->original)) {
            $this->create_product_user_relations($product_adding->original['global_product_id']);
        }
        else {
            return $product_adding;
        }
    }

    public function create_product_user_relations($product_id, $user_id = null) {
        $new_user_relatione = new User_product;
        $new_user_relatione['product_id'] = $product_id;
        if($user_id == null){
            $new_user_relatione['user_id'] = Auth::user()->id;
        }
        else{
            $new_user_relatione['user_id'] = $user_id;
        }
        $new_user_relatione -> save();
    }

    public function change_user_relation(Request $request) 
    {
        if($request['data']['old_user_id'] != null && $request['data']['old_user_id'] != []){
            $user_relatione = User_product::where('product_id', '=', $request['data']['product_id'])->where('user_id', '=', $request['data']['old_user_id'])->first();
            if($user_relatione != []){
                $user_relatione['user_id'] = $request['data']['new_user_id'];
                $user_relatione -> save();
            }
        }
        else{
            $this->create_product_user_relations($request['data']['product_id'], $request['data']['new_user_id']);
        }
    }

    /**
     * Get user relations for a specific product
     * Returns the user(s) associated with the product
     */
    public function get_product_user_relation(Request $request)
    {
        $product_id = $request->product_id;
        
        $user_relations = User_product::where('product_id', '=', $product_id)->get();
        
        if($user_relations->isEmpty()){
            return response()->json([
                'status' => 'no_relation',
                'message' => 'This product does not have any user relation',
                'data' => []
            ]);
        }
        
        $users = [];
        foreach($user_relations as $relation){
            $user = User::where('id', '=', $relation->user_id)->first();
            if($user){
                $users[] = $user;
            }
        }
        
        if(count($users) === 1){
            return response()->json([
                'status' => 'single_relation',
                'message' => 'Single user relation found',
                'data' => $users[0]
            ]);
        }
        
        // Multiple user relations - this is an error state
        return response()->json([
            'status' => 'multiple_relations',
            'message' => 'This product has multiple user relations. Please fix this.',
            'data' => $users
        ]);
    }

    /**
     * Fix duplicate user relations by keeping only the first one
     */
    public function fix_product_user_relation(Request $request)
    {
        $product_id = $request->product_id;
        
        $user_relations = User_product::where('product_id', '=', $product_id)->orderBy('id', 'asc')->get();
        
        if($user_relations->count() <= 1){
            return response()->json([
                'status' => 'success',
                'message' => 'No duplicate relations found'
            ]);
        }
        
        // Keep the first relation and delete the rest
        $first_relation = $user_relations->first();
        $relations_to_delete = $user_relations->skip(1);
        
        foreach($relations_to_delete as $relation){
            $relation->delete();
        }
        
        $user = User::where('id', '=', $first_relation->user_id)->first();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Duplicate relations removed. Single user relation kept.',
            'data' => $user
        ]);
    }
    
    public function get_product_editing_data(Request $request)
    {
        $product = product::where('id', '=', $request->product_id)->first();
        $us_product = $product->us_product;
        // $ru_product = $product->ru_product;
        $ka_product = $product->ka_product;
        $data = [
            'global_product' => $product,
            'us_product' => $us_product,
            // 'ru_product' => $ru_product,
            'ka_product' => $ka_product
        ];
        return $data;
        // dd($data);
    }

    public function edit_product(Request $request)
    {
        $auth = PermissionService::authorize('product_category', 'edit');
        // if ($auth) return $auth;
        // $data = $request->data;
        $data = json_decode($request->data, true );

        $validator = Validator::make($data['global_product'], [
            // 'category_id' => 'required',

            'subcategory_id' => 'required',
            'brand_id' => 'required',
            'sale_type' => 'required|in:custom_production,online_order',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'validation' => $validator->messages()
            ], 422);
        }

        $article_editing = ProductService::edit_content(Product::class, Locale_product::class, '_product', $request);

        if(array_key_exists('validation', $article_editing->original)){
            return $article_editing;
        }
    }

    public function update(Request $request, $id)
    {
        $page_product = Product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '=', $id)
                            ->first();

        $global_products = Product::latest('id')
                            ->where('published', '=', 1)
                            ->where('id', '!=', $page_product->id)
                            ->where('url_title', '!=', $request->url_title)
                            ->where('category_id', '=', $page_product->category_id)
                            ->get();
                            
        return ProductService::get_locale_product($global_products);
    }

    public function del_product(Request $request)
    {
        $auth = PermissionService::authorize('product_category', 'del');

        ProductService::del_content($request->product_id, Product::class, Locale_product::class, '_product', 'image', 'images/product_option_img/');
    }
}

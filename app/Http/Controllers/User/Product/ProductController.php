<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Us_product;
use App\Models\Ru_product;
use App\Models\Ka_product;

use App\Models\Favorite_product;


class ProductController extends Controller
{
    public function product_list_page(Request $request)
    {
        if (view()->exists('user.my_products_list')) {
            $data = [
    			'title'=>'Shop',
    			
                'page_name'=>'Products',

    			'shop'=>1,
                'num' => 1,

                'active' => 'product list',
    		    'table_1_name'=> '$table_1_name',
            ];
            return view('user.my_products_list',$data);
    	}
    	abort(404);
    }

    public function get_product_data(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

    		$products = product::latest('id')->get();
    		// $products = product::latest('id')->where('user_id', '=', Auth::user()->id)->get();
            $products_count = product::count();
            $favorite_products = Favorite_product::get();
            // dd($products[1]->title);
            $tags = product::get();
            $tags_count = product::count();

            $price_array = array();
            foreach ($products as $product) {
                if ($product->discount != null) {
                    $old_price = $product->price;
                    $sale = $product->discount;
                    $price_x_sale = $sale * $old_price;
                    $var_1 = $price_x_sale/100;
                    $new_price = $old_price - $var_1;
                    // dd($products[0]);
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
                }
                else{
                    $old_price = $product->price;
                    array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>'0', 'new_price'=>$old_price]);
                }
            }            

            $favorite_products_array = array();
            foreach ($favorite_products as $favorite) {
                array_push($favorite_products_array, ['favorite_id'=>$favorite->id, 'favorite_product_id'=> $favorite->product_id, 'user_id'=>$favorite->user_id]);
            }
            // dd($favorite_products_array);
            // foreach ($price_array as $price) {
            //     print_r($price['product_id']);
            //     echo "<hr />";
            // }
            // dd($price_array);
            // dd($products[0]);
            // array_push($products, $price_array);
            // if ($x > 0) {
            //     $new_price = 0;
            // }

    		// $data = [
    		// 	'title'=>'Shop',
    		// 	'products'=>$products,
            //     'products_count'=>$products_count,
            //     'favorite_products_array'=>$favorite_products_array,
            //     'tags'=>$tags,
            //     'tags_count'=>$tags_count,
            //     'page_name'=>'Products',

            //     'price_array'=>$price_array,
            //     'loop_num_var' => 0,

    		// 	'shop'=>1,
            //     'num' => 1,

            //     'active' => 'product list',
            //     // 'table_1_name' => ''
            //     'table_1_title'=>'1',
    		//     'table_1_pablic' => '',
    		//     'table_1_name'=> '$table_1_name',
                
            //     'articles_link' => 'other_page',
            //     'image_dir' => 'shop_img',
            // ];

            return $products;
    		
    }

    public function create_temporary_product()
    {
        $product = new Product();
        $product['url_title'] = 'Temporary product';
        $product['published']=0;
        $product -> save();

        $product_ka = new Ka_product();
        $product_ka['title']="Ka temporary product";
        $product_ka -> save();

        $product_ru = new Ru_product();
        $product_ru['title']="Ru temporary product";
        $product_ru -> save();

        $product_us = new Us_product();
        $product_us['title']="Us temporary product";
        $product_us -> save();
    }

    public function add_product_page(Request $request)
    {
        if (view() -> exists('user.components.forms.products.product_add_form')) {
            $category = $request->category;
            $data=[
                'category' => $category,
                'title' => 'New '.$category,
                'back_btn' => 'home',
                'add_title' => 'Add '.$category,
                'add_active' => 'Add '.$category,
                
                'add_form' => 'articleAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.products.product_add_form', $data);
        }
        abort(404);
    }

    public function edit_product_page()
    {
        $old = $article -> toArray();

        $global_article_categoyr = Article::where('id',strip_tags($request->id))->get();
        foreach ($global_article_categoyr as $article_category) {
            $category = $article_category->category;
        }
        if (view()->exists('user.components.forms.products.product_edit_form')) {
            $data = [
                'category' => $category,
                // 'title' => 'Edit article - '.$old['title'],
                "editing_article_id" => $request->id,
                'title' => 'Edit title',
                'data' => $old,
                'back_btn' => 'home',
                'edit_title' => 'Edit article',
                'edit_active' => 'Edit article',
                
                'edit_form' => 'home',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            // dd($data);
            return view('user.components.forms.products.product_edit_form', $data);
        }
    }

    public function del_product(Request $request)
    {
        if ($request->isMethod('post')) {
            $global_id=$request->id;

            $global_product = Product::where('id',strip_tags($global_id))->first();
            $us_product = Us_product::where('id',strip_tags($global_product->us_product_id))->first();
            $ru_product = Ru_product::where('id',strip_tags($global_product->ru_product_id))->first();
            $ka_product = Ka_product::where('id',strip_tags($global_product->ka_product_id))->first();

            // dd($global_product->us_product_id);

            // delete product from db
            $global_product ->delete();
            $us_product ->delete();
            $ru_product ->delete();
            $ka_product ->delete();
        }
    }
}

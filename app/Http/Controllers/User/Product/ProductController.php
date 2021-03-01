<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Us_product;
use App\Models\Ru_product;
use App\Models\Ka_product;

use App\Models\Favorite_product;

use App\Models\Product_image;

use App\Services\imageControllService;

class ProductController extends Controller
{
    public function product_list_page(Request $request)
    {
        if (view()->exists('user.data_table')) {
            $data = [
                'table_1'=>'Products',
    		    'table_1_name' => 'Products',
    		    'table_1_add_url'=>'productAddPage',
				'table_1_edit_url'=>'products/edit_product/',
				'table_1_get_route'=>'products/get_product_data',
                'table_1_del'=>"/products/del/",
                
                'table_2' => "categoryies",
    		    'table_2_name' => 'Categories',
    		    'table_2_add_url'=>'categoryAddForm',
    		    'table_2_edit_url'=>'/products/category/edit/form/',
				'table_2_get_route'=>'products/get_product_category_data',
                'table_2_del'=>"/products/category/del/",
    		    
    		    'page_name' => 'Products',
    		    'active' => 'Outdoor',
                'page_route' => 'outdoor_page',

                'active' => 'product list',
            ];
            return view('user.data_table',$data);
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
        $product_ka = new Ka_product();
        $product_ka['title']="Ka temporary product";
        $product_ka -> save();

        $product_ru = new Ru_product();
        $product_ru['title']="Ru temporary product";
        $product_ru -> save();

        $product_us = new Us_product();
        $product_us['title']="Us temporary product";
        $product_us -> save();


        $products_us = US_Product::where('title','=','Us temporary product')->get();
        $products_ka = Ka_product::where('title','=','Ka temporary product')->get();
        $products_ru = Ru_product::where('title','=','Ru temporary product')->get();

        $last_us_product_id = 0;
        $last_ka_product_id = 0;
        $last_ru_product_id = 0;
        foreach ($products_us as $product_us) {
            $last_us_product_id = $product_us->id;
        }
        foreach ($products_ka as $product_ka) {
            $last_ka_product_id = $product_ka->id;
        }
        foreach ($products_ru as $product_ru) {
            $last_ru_product_id = $product_ru->id;
        }

        $product = new Product();
        $product['url_title'] = 'temporary_product';
        $product['published']=0;
        $product['us_product_id']=$last_us_product_id;
        $product['ru_product_id']=$last_ru_product_id;
        $product['ka_product_id']=$last_ka_product_id;
        $product -> save();
    }

    public function del_temporary_product(Request $request)
    {
        if ($request->isMethod('post')) {
            $product_id=$request->id;
            $deleting_product_images = Product_image::where('product_id','=',$product_id)->get();
            $count_deleting_product_images = Product_image::where('product_id','=',$product_id)->count();
            
            if ($count_deleting_product_images > 0) {
                foreach ($deleting_product_images as $product_image) {
                    $deliting_product_image = Product_image::where('id',strip_tags($product_image->id))->first();
                    $deliting_product_image ->delete();
                }
            }

            $product = Product::where('id','=', $product_id)->first();
            $product_us = US_Product::where('id','=',$product->us_product_id)->first();
            $product_ka = Ka_product::where('id','=',$product->ka_product_id)->first();
            $product_ru = Ru_product::where('id','=',$product->ru_product_id)->first();

            $product ->delete();
            $product_us ->delete();
            $product_ka ->delete();
            $product_ru ->delete();
        }
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

    public function edit_product_page(Request $request)
    {
        if (view()->exists('user.components.forms.products.product_edit_form')) {
            $product_id = $request->id;
            $data = [
                'editing_article_id' => $request->id
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

            // delete product images
            $product_images = Product_image::where('product_id',strip_tags($global_id))->get();
            $product_images_count = Product_image::where('product_id',strip_tags($global_id))->count();
            if ($product_images_count > 0) {
                foreach ($product_images as $product_image) {
                    imageControllService::image_delete('product_img', $product_image, $request);
                    $product_image ->delete();
                }
            }

            // delete product from db
            $global_product ->delete();
            $us_product ->delete();
            $ru_product ->delete();
            $ka_product ->delete();
        }
    }
    public function get_temporary_product_editing_data()
    {
        $temporary_product = Product::where("url_title","=","temporary_product")->get();
        foreach ($temporary_product as $product) {
            $last_temporary_product_id = $product->id;
        }

        return(
            $data = [
                "last_temporary_product_id" => $last_temporary_product_id,
            ]
        );
    }
    public function get_product_image(Request $request)
    {
        $product_images = Product_image::where('product_id',"=", $request->product_id)->get();
        return(
            $data = [
                "product_images" => $product_images,
            ]
        );
    }

    public function get_product_editing_data(Request $request)
    {
        $product = Product::where('id',strip_tags($request->editing_product_id))->first();
        
        $us_product = Us_product::where('id',strip_tags($product->us_product_id))->first();
        $ru_product = Ru_product::where('id',strip_tags($product->ru_product_id))->first();
        $ka_product = Ka_product::where('id',strip_tags($product->ka_product_id))->first();

        return(
            $data = [
                "editing_product" => $product,
                "editing_product_us" => $us_product,
                "editing_product_ru" => $ru_product,
                "editing_product_ka" => $ka_product,
            ]
        );
    }

    public function upload_product_image(Request $request)
    {
        if ($request->hasFile('profile_pic')){  
            // rename file
            $file      = $request->file('profile_pic');
            $filename  = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $pieces = explode( '.', $filename );
            $file_new_name = $pieces[0].'_('.date('Y-m-d-H-m-s').').'.$extension;
            
            // push image in folder
            $file->move(public_path('images/product_img'), $file_new_name);
            $product = new product_image();
            // $product = product_image::where('id',strip_tags($last_global_product_id))->first();
            $product['image']=$file_new_name;
            $product['product_id']=$request->product_id;
            $product -> save();

            return response()->json(["message" => "Image Uploaded Succesfully"]);
        } 
        else{
            return response()->json(["message" => "Image Uploaded Error."]);
        }
    }

    public function update_product_image(Request $request)
    {
        # code...
    }
    public function del_product_image(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);

        if ($request->isMethod('post')) {
            $product_image_id=$request->image_id;

            $product_image = Product_image::where('id',strip_tags($product_image_id))->first();

            // delite image
            imageControllService::image_delete('product_img', $product_image, $request);

            // delete product from db
            $product_image ->delete();
        }
    }




    public function favorite()
    {
        # code...
    }
}

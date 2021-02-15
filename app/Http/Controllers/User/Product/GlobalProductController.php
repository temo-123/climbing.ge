<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalProductController extends Controller
{
    // private $image_dir = "/images/shop_img";
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function add_global_product(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        $image_dir = "/images/shop_img";

        if ($request -> isMethod('post')) {
            $input = $request -> except('_token');

            $this->product_validate($request);

            $product = new product();

            $product['image_1'] = ImageEditService::image_upload($image_dir, "image_1", $request);
            $product['image_2'] = ImageEditService::image_upload($image_dir, "image_2", $request);
            $product['image_3'] = ImageEditService::image_upload($image_dir, "image_3", $request);
            $product['image_4'] = ImageEditService::image_upload($image_dir, "image_4", $request);
            $product['user_id'] = Auth::user()->id;
            $product['url_title'] = $this->get_url_title($request->title);
            $product['title'] = $request->title;
            $product['text'] = $request->text;
            $product['price'] = $request->price;
            $product['currency'] = $request->currency;
            $product['category'] = $request->category;
            
            if ($request->general_image == NULL) {
                $product['general_image'] = 1;
            }

            // $product -> fill($input);
            $product -> save();

            if ($product->save()) {
                return  redirect()->route('products_list') -> with('status', 'Product addid');
            }
        }
        
        if (view() -> exists('user.components.forms.products_form')) {
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
            return view('user.components.forms.products_form', $data);
        }
        abort(404);
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function edit(Product $product, Request $request)
    {
        $image_dir = '/images/shop_img';

        if ($request->isMethod('post')) {
            $input = $request -> except('_token');

            $this->product_validate($request);

            $product['image_1'] = ImageEditService::image_update($image_dir, $product, $input, "image_1", $request);
            $product['image_2'] = ImageEditService::image_update($image_dir, $product, $input, "image_2", $request);
            $product['image_3'] = ImageEditService::image_update($image_dir, $product, $input, "image_3", $request);
            $product['image_4'] = ImageEditService::image_update($image_dir, $product, $input, "image_4", $request);

            $product->touch();

            if ($product->update()) {
                return redirect()->route('products_list')->with('status','product updated!'); //text
            }
            else{
                return redirect()->route('products_list')->with('error','Error! product not updated'); //text
            }
        }
        $old = $product -> toArray();

        if (view()->exists('user.components.forms.products_form')) {
            $data = [
                'title' => 'Edit news - '.$old['title'],
                'data' => $old,

                'edit_form'=>'productEdit',
                'edit_title'=>'test',
                'edit_active'=>'test 2',
                'published' => 1,
                'description' => 1,
                'text' => 1,

                'image' => $image_dir
            ];          
            return view('user.components.forms.products_form', $data);
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function delete(product $product, Request $request)
    {
        $image_dir = '/images/shop_img';

        if ($request->isMethod('delete')) {
            ImageEditService::image_delite($image_dir, $product, 'image_1');
            ImageEditService::image_delite($image_dir, $product, 'image_2');
            ImageEditService::image_delite($image_dir, $product, 'image_3');
            ImageEditService::image_delite($image_dir, $product, 'image_4');

            // delete product from db
            $product -> delete();

            return back()->with('good_status', 'product delited!'); //text
        }
        else{
            return back()->with('bed_status', 'Error! product not deleted!'); //text
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function favorite()
    {
        if (view()->exists('user.favorite_products')) {

            $favorite_products = Favorite_product::where('user_id', '=', Auth::user()->id)->get();
    		$products = product::get();
            
            $my_favorite_products = array();

            foreach ($favorite_products as $favorite_product) {
                foreach ($products as $product) {
                    // dd($favorite_product->id);
                    if ($product->id == $favorite_product->product_id) {
                        // print_r($product->id);
                        array_push($my_favorite_products, [product::where('id', '=', $product->id)->get()]);
                    }
                }
            }
            // $products = product::latest('id')->where('user_id', '=', Auth::user()->id)->get();
            // $products_count = product::count();
            // $favorite_products = Favorite_product::get();
            // // dd($products[1]->title);
            // $tags = product::get();
            // $tags_count = product::count();
            // $price_array = array();
            // foreach ($products as $product) {
            //     if ($product->discount != null) {
            //         $old_price = $product->price;
            //         $sale = $product->discount;
            //         $price_x_sale = $sale * $old_price;
            //         $var_1 = $price_x_sale/100;
            //         $new_price = $old_price - $var_1;
            //         // dd($products[0]);
            //         array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>$sale, 'new_price'=>$new_price]);
            //     }
            //     else{
            //         $old_price = $product->price;
            //         array_push($price_array, ['product_id'=>$product->id, 'old_price'=>$old_price, 'sale'=>'0', 'new_price'=>$old_price]);
            //     }
            // }            

            // $favorite_products_array = array();
            // foreach ($favorite_products as $favorite) {
            //     array_push($favorite_products_array, ['favorite_id'=>$favorite->id, 'favorite_product_id'=> $favorite->product_id, 'user_id'=>$favorite->user_id]);
            // }

    		$data = [
    			'title'=>'My favorite products',
                'page_name'=>'favorite_products',
    			'my_favorite_products' => $my_favorite_products
    		];
    		return view('user.favorite_products',$data);
    	}
    	abort(404);
    }









    private function product_validate($request)
    {
        $request->validate([
            'category' => 'required',
            'title' => 'required|max:25',
            'text' => 'required',
            'price' => 'required|max:5',
            'currency' => 'required|max:4',
            'image_1' => 'required',
        ]);
    }

   

    public function get_url_title($title)
    {
        $title_pieces = explode(" ", $title);
        $url_title = implode("_", $title_pieces);

        return $url_title;
    }
}

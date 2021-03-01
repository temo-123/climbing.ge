<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Us_product;
use App\Models\Ka_product;
use App\Models\Ru_product;
use Auth;

use App\Services\imageControllService;

use App\Services\URLTitleService;

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

        if ($request -> isMethod('post')) {
            $last_globale_id = 0;
            $last_us_product_id = 0;
            $last_ka_product_id = 0;
            $last_ru_product_id = 0;

            $global_product = product::where('url_title', '=', 'temporary_product')->get();
            foreach ($global_product as $global) {
                $last_globale_id = $global->id;
            }

            $us_articl = Us_product::where('title', '=', 'Us temporary product')->get();
            foreach ($us_articl as $us) {
                $last_us_product_id = $us->id;
            }

            $ka_articl = Ka_product::where('title', '=', 'Ka temporary product')->get();
            foreach ($ka_articl as $ka) {
                $last_ka_product_id = $ka->id;
            }

            $ru_articl = Ru_product::where('title', '=', 'Ru temporary product')->get();
            foreach ($ru_articl as $ru) {
                $last_ru_product_id = $ru->id;
            }

            $url_title = URLTitleService::get_url_title($request->us_title_for_url_title);

            $product = product::find($last_globale_id);

            $product['user_id'] = Auth::user()->id;
            $product['url_title'] = $url_title;

            // $product['discount']=$request->discount;
            $product['published'] = $request->published;
            $product['price'] = $request->price;
            $product['currency'] = $request->currency;
            $product['category_id'] = $request->category_id;

            $product['us_product_id'] = $last_us_product_id;
            $product['ru_product_id'] = $last_ru_product_id;
            $product['ka_product_id'] = $last_ka_product_id;
            
            $product -> update();
        }
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function edit_global_product(Product $product, Request $request)
    {
        if ($request->isMethod('post')) {
            $product = Product::where('id',strip_tags($request->id))->first();
            
            // $product['url_title'] = $url_title;
            // $product['discount']=$request->discount;
            $product['price']=$request->price;
            $product['currency']=$request->currency;
            $product['category_id']=$request->category;
            $product['published'] = $request->published;
            // $product['user_id']=Auth::user()->id;

            // $product['us_product_id']=$last_us_product_id;
            // $product['ru_product_id']=$last_ru_product_id;
            // $product['ka_product_id']=$last_ka_product_id;
            $product -> update();
        }
    }

    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function delete(product $product, Request $request)
    {
        if ($request->isMethod('delete')) {

            $image_dir = '/images/shop_img';
            imageControllService::image_delete($image_dir, $sector_image, $request);

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

}

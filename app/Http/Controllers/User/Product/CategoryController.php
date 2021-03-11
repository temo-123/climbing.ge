<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product_category;

class CategoryController extends Controller
{
    public function product_add_form(Request $request)
    {
        if (view() -> exists('user.components.forms.products.category_add_form')) {
            
            $data=[
                'title' => 'New category',
                'back_btn' => 'home',
                'add_title' => 'Add category',
                'add_active' => 'Add category',
                
                'add_form' => 'categoryAdd',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.products.category_add_form', $data);
        }
        abort(404);
    }
    public function add_product_category(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'seller']);

        if ($request -> isMethod('post')) {
            $this->category_validate($request);

            $category = new Product_category();

            $category['us_name'] = $request->us_name;
            $category['ka_name'] = $request->ka_name;
            $category['ru_name'] = $request->ru_name;

            $category -> save();
        }
    }

    public function product_edit_form(Request $request)
    {
        if (view() -> exists('user.components.forms.products.category_edit_form')) {
            
            $data=[
                'title' => 'New category',
                'back_btn' => 'home',
                'edit_title' => 'edit category',
                'edit_active' => 'edit category',

                "editing_category_id" => $request->id,
                
                'edit_form' => 'categoryedit',
                
                'url_title' => 1,
                'text' => 1, 
                'published'=>'1',
                'link'=>'1',
                'article_filtr'=>'1',
                
                'image' => 'article_img',
            ];
            return view('user.components.forms.products.category_edit_form', $data);
        }
        abort(404);
    }
    public function edit_product_category(Request $request)
    {
        $request->user()->authorizeRoles(['admin', 'seller']);

        if ($request -> isMethod('post')) {
            $this->category_validate($request);
            
            $category = Product_category::find($request->id);

            $category['us_name'] = $request->us_name;
            $category['ka_name'] = $request->ka_name;
            $category['ru_name'] = $request->ru_name;

            $category -> update();
        }
    }

    public function del_product_category(Request $request)
    {
        if ($request->isMethod('post')) {
            $product_id=$request->id;

            $deleted_category = Product_category::where('id',strip_tags($product_id))->first();

            // delete product from db
            $deleted_category -> delete();
        }
    }

    public function get_product_category_data()
    {
        return Product_category::latest('id')->get();
    }

    public function get_editing_category_data(Request $request)
    {

        $product_category = Product_category::where('id',strip_tags($request->id))->first();
        return(
            $data = [
                "product_category" => $product_category,
            ]
        );
    }

    private function category_validate($request)
    {
        $request->validate([
            'us_name' => 'required',
            'ka_name' => 'required',
            'ru_name' => 'required',
        ]);
    }
}

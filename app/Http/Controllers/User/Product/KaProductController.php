<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ka_product;

class KaProductController extends Controller
{
    public function add_ka_product(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->ka_product_validate($request);

            $ka_articl = Ka_product::get();
            foreach ($ka_articl as $ka) {
                $last_ka_product_id = $ka->id;
            }

            $ka_product = Ka_product::find($last_ka_product_id);
            // $ka_product = new Ka_product();

            $ka_product['title']=$request->ka_title;
            $ka_product['short_description']=$request->ka_short_description;
            $ka_product['text']=$request->ka_text;
            $ka_product['meta_keyword']=$request->ka_meta_keyword;

            $ka_product -> update();
        }
    }

    public function edit_ka_product(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->ka_product_validate($request);

            $ka_product = Ka_product::where('id',strip_tags($request->id))->first();
            
            $ka_product->title=$request->ka_title;
            $ka_product->short_description=$request->ka_short_description;
            $ka_product->text=$request->ka_text;
            $ka_product->meta_keyword=$request->ka_meta_keyword;
            
            $ka_product -> update();
        }
    }


    private function ka_product_validate($request)
    {
        $request->validate([
            'ka_title' => 'required',
            'ka_short_description' => 'required',
            'ka_text' => 'required',
        ]);
    }
}

<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Us_product;

class UsProductController extends Controller
{
    public function add_us_product(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');
            $this->us_product_validate($request);

            $us_articl = us_product::get();
            foreach ($us_articl as $us) {
                $last_us_product_id = $us->id;
            }

            $us_product = us_product::find($last_us_product_id);
            // $us_product = new us_product();

            $us_product['title']=$request->us_title;
            $us_product['short_description']=$request->us_short_description;
            $us_product['text']=$request->us_text;
            $us_product['meta_keyword']=$request->us_meta_keyword;

            $us_product -> update();
        }
    }

    public function edit_us_product(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->us_product_validate($request);
            
            $us_product = Us_product::where('id',strip_tags($request->id))->first();
            
            $us_product->title=$request->us_title;
            $us_product->short_description=$request->us_short_description;
            $us_product->text=$request->us_text;
            $us_product->meta_keyword=$request->us_meta_keyword;
            
            $us_product -> update();
        }
    }


    private function us_product_validate($request)
    {
        $request->validate([
            'us_title' => 'required',
            'us_short_description' => 'required',
            'us_text' => 'required',
        ]);
    }
}

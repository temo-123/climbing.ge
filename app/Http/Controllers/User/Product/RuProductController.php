<?php

namespace App\Http\Controllers\User\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Ru_product;

class RuProductController extends Controller
{
    public function add_ru_product(Request $request)
    {
        $request->user()->authorizeRoles(['manager', 'admin']);
        if ($request -> isMethod('post')) {
            // $input = $request -> except('_token');

            $ru_articl = ru_product::get();
            foreach ($ru_articl as $ru) {
                $last_ru_product_id = $ru->id;
            }

            $ru_product = ru_product::find($last_ru_product_id);
            // $ru_product = new ru_product();

            $ru_product['title']=$request->ru_title;
            $ru_product['short_description']=$request->ru_short_description;
            $ru_product['text']=$request->ru_text;
            $ru_product['meta_keyword']=$request->ru_meta_keyword;

            $ru_product -> update();
        }
    }

    public function edit_ru_product(Request $request)
    {
        if ($request->isMethod('post')) {
            $ru_product = Ru_product::where('id',strip_tags($request->id))->first();
            
            $ru_product['title']=$request->title;
            $ru_product['short_description']=$request->short_description;
            $ru_product['text']=$request->text;
            $ru_product['meta_keyword']=$request->meta_keyword;
            
            $ru_product -> update();
        }
    }
}

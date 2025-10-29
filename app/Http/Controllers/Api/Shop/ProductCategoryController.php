<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Product_category;
use App\Models\Shop\product_options;

use Validator;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $categories = Product_category::latest('id')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $validate = $this->validation($request);

    //     if ($validate != null) {
    //         return($validate);
    //     }
    //     else{
    //         $new_product_category = new Product_category;

    //         $new_product_category['us_name'] = $request->data['us_name'];
    //         $new_product_category['ka_name'] = $request->data['ka_name'];
    //         // $new_product_category['ru_name'] = $request->data['ru_name'];

    //         $new_product_category -> save();
    //     }
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product_category::where("id", "=", $id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $validate = $this->validation($request);

    //     if ($validate != null) {
    //         return($validate);
    //     }
    //     else{
    //         // dd($request->editing_data['us_name']);
    //         $editing_product_category = Product_category::where("id", "=", $id)->first();

    //         $editing_product_category['us_name'] = $request->data['us_name'];
    //         $editing_product_category['ka_name'] = $request->data['ka_name'];
    //         // $editing_product_category['ru_name'] = $request->data['ru_name'];

    //         $editing_product_category -> save();
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $deleted_product_category = Product_category::where("id", "=", $id)->first();
    //     $deleted_product_category -> delete();
    // }

    // public function validation($request)
    // {
    //     $validator = validator($data = $request->data, [
    //         'us_name' => 'required',
    //         // 'ru_name' => 'required',
    //         'ka_name' => 'required',
    //     ]);
    
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->messages(),
    //         ], 422);
    //     }
    // }
}

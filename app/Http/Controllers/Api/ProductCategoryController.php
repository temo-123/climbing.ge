<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Product_category;
use App\Models\product_options;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_product_category = new Product_category;

        $new_product_category['us_name'] = $request->data['us_name'];
        $new_product_category['ka_name'] = $request->data['ka_name'];
        $new_product_category['ru_name'] = $request->data['ru_name'];

        $new_product_category -> save();
    }

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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->editing_data['us_name']);
        $editing_product_category = Product_category::where("id", "=", $id)->first();

        $editing_product_category['us_name'] = $request->editing_data['us_name'];
        $editing_product_category['ka_name'] = $request->editing_data['ka_name'];
        $editing_product_category['ru_name'] = $request->editing_data['ru_name'];

        $editing_product_category -> save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted_product_category = Product_category::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }
}

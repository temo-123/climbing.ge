<?php

namespace App\Http\Controllers\Api\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Sale_code;

class SaleCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sale_code::latest('id')->get();
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
        $new_sale_adres = new Sale_code;

        $new_sale_adres['discount'] = $request->data['discount'];
        $new_sale_adres['code'] = $request->data['code'];
        $new_sale_adres['action_data'] = $request->data['action_data'];
        $new_sale_adres['one_time_code'] = $request->data['one_time_code'];

        $new_sale_adres -> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sale_code::where("id", "=", $id)->first();
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
        // dd($request->editing_data['discount']);
        $editing_product_category = Sale_code::where("id", "=", $id)->first();

        $editing_product_category['discount'] = $request->editing_data['discount'];
        $editing_product_category['code'] = $request->editing_data['sale_code'];
        $editing_product_category['action_data'] = $request->editing_data['validity_date'];
        $editing_product_category['one_time_code'] = $request->editing_data['one_time_code'];

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
        $deleted_product_category = Sale_code::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }
}

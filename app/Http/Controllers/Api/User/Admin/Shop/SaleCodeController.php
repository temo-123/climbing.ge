<?php

namespace App\Http\Controllers\Api\User\Admin\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Shop\Sale_code;
use App\Services\PermissionService;

class SaleCodeController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_sale_code(Request $request)
    {
        $auth = PermissionService::authorize('sale_code', 'add');
        if ($auth) return $auth;
        
        $new_sale_adres = new Sale_code;

        $new_sale_adres['discount'] = $request->data['discount'];
        $new_sale_adres['code'] = $request->data['code'];
        $new_sale_adres['action_data'] = $request->data['action_data'];
        $new_sale_adres['one_time_code'] = $request->data['one_time_code'];

        $new_sale_adres -> save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit_sale_code(Request $request, $id)
    {
        $auth = PermissionService::authorize('sale_code', 'edit');
        if ($auth) return $auth;
        
        // dd($request->editing_data['discount']);
        $editing_product_category = Sale_code::where("id", "=", $id)->first();

        $editing_product_category['discount'] = $request->editing_data['discount'];
        $editing_product_category['code'] = $request->editing_data['sale_code'];
        $editing_product_category['action_data'] = $request->editing_data['validity_date'];
        $editing_product_category['one_time_code'] = $request->editing_data['one_time_code'];

        $editing_product_category -> save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function get_editing_sale_code($id)
    {
        $auth = PermissionService::authorize('sale_code', 'view');
        if ($auth) return $auth;
        
        return Sale_code::where("id", "=", $id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function del_sale_code($id)
    {
        $auth = PermissionService::authorize('sale_code', 'del');
        if ($auth) return $auth;
        
        $deleted_product_category = Sale_code::where("id", "=", $id)->first();
        $deleted_product_category -> delete();
    }
}


<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Warehouse;
use App\Models\User\ProductOption;

class WarehouseController extends Controller
{
    public function get_warehouses () {
        return Warehouse::all();
    }

    public function add_warehouses (Request $request) {
        $warehouse = Warehouse::create($request->all('data')['data']);
        return response()->json($warehouse, 201);
    }

    public function edit_warehouse (Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $warehouse->update($request->all('data')['data']);
        return response()->json($warehouse);
    }

    public function get_editing_warehouse (Request $request) {
        return Warehouse::find($request->id);
    }

    public function get_activ_warehouse (Request $request) {
        return Warehouse::find($request->id);
    }

    public function del_warehouses (Request $request) {
        $deleted = Warehouse::where('id', $request->id)->delete();
        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }

    // --- Warehouse-ProductOption Relations ---

    public function add_product_option_to_warehouse(Request $request) {
        $warehouse = Warehouse::find($request->warehouse_id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }
        $warehouse->productOptions()->attach($request->product_option_id);
        return response()->json(['success' => true]);
    }

    public function remove_product_option_from_warehouse(Request $request) {
        $warehouse = Warehouse::find($request->warehouse_id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }
        $warehouse->productOptions()->detach($request->product_option_id);
        return response()->json(['success' => true]);
    }

    public function get_warehouse_product_options(Request $request) {
        $warehouse = Warehouse::find($request->warehouse_id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }
        return $warehouse->productOptions;
    }
}

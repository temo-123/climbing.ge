<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Warehouse;
use App\Models\Shop\Product_option;

class WarehouseController extends Controller
{
    public function get_warehouses () {
        return Warehouse::all();
    }

    public function add_warehouse (Request $request) {
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

    public function get_warehouse_data(Request $request) {
        return Warehouse::find($request->id);
    }

    public function get_activ_warehouse (Request $request) {
        return Warehouse::find($request->id);
    }

    public function del_warehouse (Request $request) {
        $deleted = Warehouse::where('id', $request->id)->delete();
        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }

    // --- Warehouse-ProductOption Relations ---

    public function add_product_option_to_warehouse(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $productOption = Product_option::find($request->product_option_id);
        if (!$productOption) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        // Attach with quantity (default to 0 if not provided)
        $quantity = $request->quantity ?? 0;
        $warehouse->productOptions()->attach($request->product_option_id, ['quantity' => $quantity]);

        return response()->json(['success' => true, 'message' => 'Product option added to warehouse']);
    }

    public function remove_product_option_from_warehouse(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }
        $warehouse->productOptions()->detach($request->product_option_id);
        return response()->json(['success' => true]);
    }

    public function get_warehouse_product_options(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }
        return $warehouse->productOptions()->with(['images', 'product'])->get();
    }

    public function edit_product_option_quantity(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $productOption = Product_option::find($request->product_option_id);
        if (!$productOption) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        $quantity = $request->quantity ?? 0;

        if ($quantity <= 0) {
            // If quantity is 0 or negative, remove the product option from warehouse
            $warehouse->productOptions()->detach($request->product_option_id);
            return response()->json(['success' => true, 'message' => 'Product option removed from warehouse due to zero quantity']);
        }

        // Update the quantity in the pivot table
        $warehouse->productOptions()->updateExistingPivot($request->product_option_id, [
            'quantity' => $quantity
        ]);

        return response()->json(['success' => true, 'message' => 'Quantity updated successfully']);
    }

    public function delete_product_option_from_warehouse(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $productOption = Product_option::find($request->product_option_id);
        if (!$productOption) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        $warehouse->productOptions()->detach($request->product_option_id);
        return response()->json(['success' => true, 'message' => 'Product option removed from warehouse']);
    }

    public function get_product_option_details(Request $request) {
        $warehouse = Warehouse::find($request->id);
        if (!$warehouse) {
            return response()->json(['error' => 'Warehouse not found'], 404);
        }

        $productOption = Product_option::find($request->product_option_id);
        if (!$productOption) {
            return response()->json(['error' => 'Product option not found'], 404);
        }

        // Get the pivot data for this warehouse and product option
        $pivotData = $warehouse->productOptions()->where('product_option_id', $request->product_option_id)->first();
        return response()->json([
            'product_option' => $productOption,
            'warehouse_quantity' => $pivotData ? $pivotData->pivot->quantity : 0
        ]);
    }
}

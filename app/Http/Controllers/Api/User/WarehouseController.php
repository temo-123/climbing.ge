<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User\Warehouse;

class WarehouseController extends Controller
{
    public function get_warehouses () {
        return Warehouse::get();
    }

    public function add_warehouses () {

    }

    public function edit_warehouse () {

    }

    public function get_editing_warehouse () {

    }

    public function get_activ_warehouse () {

    }

    public function del_warehouses () {

    }
}

<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\General_info;

class GeneralInfoController extends Controller
{
    public function get_all_general_infos()
    {
        return General_info::get();
    }

    public function show($id)
    {
        return General_info::where("id", "=", $id)->first();
    }

}

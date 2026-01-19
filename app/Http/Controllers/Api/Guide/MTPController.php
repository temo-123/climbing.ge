<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

use Validator;

class MTPController extends Controller
{
    public function index()
    {
        return MTP::latest('id')->get();
    }

    public function get_mtps_for_forum($sector_id)
    {
        return MTP::where('sector_id','=', $sector_id)->get();
    }

    public function get_mtp_for_modal(Request $request)
    {
        $mtp = Mtp::where('id',strip_tags($request->mtp_id))->first();
        $mtp_pitchs = $mtp->pitchs;
        // dd($mtp_pitchs);

        $mtp_model_info = [];

        array_push($mtp_model_info, 
            array(
                'mtp' => $mtp,
                'mtp_pitchs' => $mtp_pitchs,
            )
        );

        return $mtp_model_info[0];
    }

}

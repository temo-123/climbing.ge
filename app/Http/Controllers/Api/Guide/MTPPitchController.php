<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Guide\Sector;
use App\Models\Guide\Route;
use App\Models\Guide\Mtp;
use App\Models\Guide\Mtp_pitch;

class MTPPitchController extends Controller
{
    public function index()
    {
        return Mtp_pitch::latest('id')->get();
    }

    public function get_pitchs(Request $request)
    {
        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
    }

    public function get_mtp_pitchs(Request $request)
    {
        return Mtp_pitch::where('mtp_id',strip_tags($request->mtp_id))->orderBy('num')->get();
        // return( $mtp_pitchs );
    }

}

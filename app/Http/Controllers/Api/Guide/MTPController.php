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
        $mtp = Mtp::with('review')->where('id', strip_tags($request->mtp_id))->first();
        $mtp_pitchs = $mtp->pitchs;

        $published_reviews = $mtp->review->where('published', 1);
        $reviews_count = $published_reviews->count();
        $reviews_stars = $reviews_count > 0 ? round($published_reviews->avg('stars'), 1) : null;

        return [
            'mtp'           => $mtp,
            'mtp_pitchs'    => $mtp_pitchs,
            'reviews_count' => $reviews_count,
            'reviews_stars' => $reviews_stars,
        ];
    }

}

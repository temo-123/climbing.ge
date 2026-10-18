<?php

namespace App\Http\Controllers\Api\Guide;

use App\Http\Controllers\Controller;
use App\Models\Guide\MtpAscent;

class MtpAscentController extends Controller
{
    /**
     * Public ascent list for one multi-pitch — climber restricted to public-safe columns.
     */
    public function get_mtp_ascents($mtp_id)
    {
        return MtpAscent::where('mtp_id', $mtp_id)
            ->with('user:id,name,surname,image')
            ->orderByDesc('ascent_date')
            ->orderByDesc('id')
            ->get(['id', 'mtp_id', 'user_id', 'ascent_date', 'ascent_time', 'ascent_style', 'comment', 'photo']);
    }
}

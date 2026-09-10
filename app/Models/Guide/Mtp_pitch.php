<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mtp_pitch extends Model
{
    public $table = 'mtp_pitchs';

    protected $fillable = [
        "mtp_id",
        "grade",
        "or_grade",
        "title",
        "text_us",
        "text_ka",
        "last_carabin",
        "height",
        "bolts",
        "bolts_type",
        "bolter",
        "first_ascent",
        "first_ascent_date",
    ];

    public function json()
    {
        return $this->hasOne(MtpPitchJson::class, 'mtp_pitch_id');
    }
}

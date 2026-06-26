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
        "bolter",
        "first_ascent",
    ];

    public function json()
    {
        return $this->hasOne(MtpPitchJson::class, 'mtp_pitch_id');
    }
}

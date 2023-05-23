<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mtp_pitch extends Model
{
    public $table = 'mtp_pitchs';

    protected $fillable = [
        "mtp_id",
        "grade",
        "or_grade",
        "title",
        "text",
        "last_carabin",
        "height",
        "bolts",
        "bolter",
        "first_ascent",
    ];
}

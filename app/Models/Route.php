<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public $table = 'routes';

    protected $fillable = [
        "sector_id",
        "gread",
        "or_gread",
        "title",
        "text",
        "last_carabin",
        "height",
        "bolts",
        "bolter",
        "first_ascent",
    ];
}

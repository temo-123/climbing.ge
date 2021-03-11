<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public $table = 'routes';

    protected $fillable = [
        "sector_id",
        "grade",
        "or_grade",
        "title",
        "text",
        "last_carabin",
        "height",
        "bolts",
        "bolter",
        "bolting_data",
        "first_ascent",
    ];
}

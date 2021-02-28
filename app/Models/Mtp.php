<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mtp extends Model
{
    public $table = 'mtps';

    protected $fillable = [
        "sector_id",
        "title",
        "text",
        "last_carabin",
        "height",
        "bolter",
        "first_ascent",
    ];
}

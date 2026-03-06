<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Sector_image extends Model
{
    protected $fillable = [
        "title",
        "image",
        "sector_id",
        "num",
    ];
}

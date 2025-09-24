<?php

namespace App\Models\Guide;

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

        // "last_carabin",
        "height",
        "bolts",
        "anchor_type",

        "author",
        "creation_data",

        "first_ascent",
        "route_json", // Add json field for canvas data
    ];

    public function review()
    {
        return $this->hasMany(Sport_route_review::class);
    }
}

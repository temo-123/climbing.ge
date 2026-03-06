<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    public $table = 'routes';

    protected $fillable = [
        "sector_id",
        "category",

        "grade",
        "or_grade",

        "name",
        "text",

        "height",
        "bolts",
        "anchor_type",

        "author",
        "creation_data",

        "first_ascent",
    ];


    public function review()
    {
        return $this->hasMany(Sport_route_review::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}

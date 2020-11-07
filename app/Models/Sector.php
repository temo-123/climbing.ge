<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    
    protected $fillable = [
        "name",
        "article_id",
        "text",
        "num",
        "all_day",
        "no_sun",
        "afternoon",
        "befornoon",
    ];
}

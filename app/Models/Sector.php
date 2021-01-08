<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    
    protected $fillable = [
        "name",
        "article_id",
        "text",
        'all_day_in_shade',
        'all_day_in_sun',
        'in_the_shade_afternoon',
        'in_the_shade_befornoon',
        'in_shade_after_10',
        'in_shade_after_15',
        'slabby',
        'vertical',
        'owerhang',
    ];
}

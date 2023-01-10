<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    
    protected $fillable = [
        "name",
        "text",

        'all_day_in_shade',
        'all_day_in_sun',
        'in_the_shade_afternoon',
        'in_the_shade_befornoon',
        'in_shade_after_10',
        'in_shade_after_15',

        'slabby',
        'vertical',
        'roof',
        'owerhang',

        "article_id",
    ];

    public function routes()
    {
        return $this->hasMany(Route::class)->latest('num');
    }

    public function sport_routes()
    {
        return $this->hasMany(Route::class)->where('category', '=', 'sport climbing')->latest('num');
    }

    public function boulder_routes()
    {
        return $this->hasMany(Route::class)->where('category', '=', 'bouldering')->latest('num');
    }

    public function images()
    {
        return $this->hasMany(Sector_image::class)->latest('num');
    }

    public function mtps()
    {
        return $this->hasMany(Mtp::class)->latest('num');
    }

	// public function sector_rocks_images()
	// {
	// 	return $this->hasOhe(Spot_rocks_image_sector::class, 'sector_id');
	// }

	public function sector_local_image()
	{
		// return $this->hasOhe(Spot_rocks_image_sector::class, 'sector_id');
        return $this->belongsToMany(Sector_local_image::class, 'sector_local_image_sector', 'sector_id', 'image_id');
	}
}

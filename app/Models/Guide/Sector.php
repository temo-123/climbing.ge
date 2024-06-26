<?php

namespace App\Models\Guide;

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

    public function article()
    {
        return $this->hasOne(Article::class, "id", "article_id");
    }

    public function routes()
    {
        return $this->hasMany(Route::class)->orderBy('num');
    }

    public function sport_routes()
    {
        return $this->hasMany(Route::class,)->where('category', '!=', 'bouldering')->orderBy('num');
    }

    public function boulder_routes()
    {
        return $this->hasMany(Route::class)->where('category', '=', 'bouldering')->orderBy('num');
    }

    public function images()
    {
        return $this->hasMany(Sector_image::class)->orderBy('num');
    }

    public function mtps()
    {
        return $this->hasMany(Mtp::class)->orderBy('num');
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

	public function sector_local_image_relation()
	{
        return $this->hasMany(sector_local_image_sector::class, 'sector_id', 'id');
	}
}

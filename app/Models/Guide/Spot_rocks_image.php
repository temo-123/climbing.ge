<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot_rocks_image extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'num',
        'article_id',
    ];

	public function sector_images()
	{
		return $this->hasOne(Sector::class, 'id');
	}

	// A photo can show several sectors of its own article, each with its own
	// independent drawing on the shared image — real close to how a single
	// sector_local_images photo can carry one layout per linked sector.
	public function jsons()
	{
		return $this->hasMany(SpotRocksImageJson::class, 'spot_rocks_image_id');
	}
}

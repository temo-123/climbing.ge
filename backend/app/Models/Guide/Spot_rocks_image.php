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
}

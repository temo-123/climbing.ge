<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spot_rocks_image_sector extends Model
{
    use HasFactory;

	public function sector()
	{
		return $this->hasMany(Sector::class, 'id');
	}

    public function images()
    {
		return $this->hasOne(Spot_rocks_image::class, 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;

	public function spot_rock_images()
	{
		return $this->hasMany(Region::class);
	}
}

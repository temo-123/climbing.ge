<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery_category extends Model
{
    use HasFactory;

	public function gallery_images()
	{
		return $this->hasMany(Gallery_images::class);
	}
}

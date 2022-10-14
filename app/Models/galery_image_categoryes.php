<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class galery_image_categoryes extends Model
{
    use HasFactory;

	public function category_images()
	{
		return $this->hasMany(Gallery::class);
	}
}

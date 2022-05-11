<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spots_rocks_image extends Model
{
    use HasFactory;

	public function sector()
	{
		return $this->hasOhe(Sector::class);
	}
}

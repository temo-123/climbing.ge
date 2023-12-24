<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport_route_review extends Model
{
    use HasFactory;

	public function route()
	{
		return $this->hasOne(Route::class, 'id', 'route_id');
	}
}

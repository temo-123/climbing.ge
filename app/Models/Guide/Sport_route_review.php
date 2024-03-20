<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport_route_review extends Model
{
    use HasFactory;

    protected $fillable = [
        'stars',
        'text',
        'climbed',
        'climbed_data',
        'route_id',
	];

	public function route()
	{
		return $this->hasOne(Route::class, 'id', 'route_id');
	}

	public function user()
	{
		return $this->belongsToMany(User::class, 'sport_route_review_user', 'review_id', 'user_id');
	}
}

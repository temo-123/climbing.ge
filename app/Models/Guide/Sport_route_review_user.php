<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sport_route_review_user extends Model
{
    use HasFactory;

    public $table = 'sport_route_review_user';

	public function review()
	{
		return $this->hasOne(Sport_route_review::class, 'id', 'review_id');
	}
}

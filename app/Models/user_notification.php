<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'add_new_gym',
        'add_new_ice_spot',
        'add_new_outdoor_spot',
        'add_new_product',
        'add_new_sector',
        'add_new_service',
        'add_new_techtip',
        'favorite_film',
        'favorite_outdoor',
        'favorite_product',
        'interested_event',
	];

	public function user()
	{
		return $this->hasOne(User::class, 'id');
	}
}

<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class User_adreses extends Model
{
    // public $table = 'articles';

    protected $fillable = [
        'name',
        'country_id',
        'city',
        'strit',
        'number',
        'floor',
        'flat',
        'entrance',
        'zip_code',
	];

	public function user()
	{
		return $this->hasOne(User::class, 'id');
	}

	public function country()
	{
		return $this->hasOne(Shiped_country::class, 'id');
	}
}

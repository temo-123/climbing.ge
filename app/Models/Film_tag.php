<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film_tag extends Model
{
    use HasFactory;

    protected $fillable = [
	    'us_name',
	    'ka_name',
	    'ru_name',
	];

    public function films()
	{
		return $this->hasMany(Film::class, 'id');
	}
}

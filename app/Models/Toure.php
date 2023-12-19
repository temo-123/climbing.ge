<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Toure extends Model
{
    use HasFactory;
    public $table = 'tours';

	public function us_toure()
	{
		return $this->hasOne(Locale_toure::class, 'id', 'us_toure_id');
	}

	public function ka_toure()
	{
		return $this->hasOne(Locale_toure::class, 'id', 'ka_toure_id');
	}

	public function ru_toure()
	{
		return $this->hasOne(Locale_toure::class, 'id', 'ru_toure_id');
	}

	public function toure_images()
	{
		return $this->hasMany(Toure_image::class, 'toure_id', 'id');
	}
}

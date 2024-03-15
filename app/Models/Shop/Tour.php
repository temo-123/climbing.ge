<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use HasFactory;
    public $table = 'tours';

	public function us_tour()
	{
		return $this->hasOne(Locale_tour::class, 'id', 'us_tour_id');
	}

	public function ka_tour()
	{
		return $this->hasOne(Locale_tour::class, 'id', 'ka_tour_id');
	}

	public function ru_tour()
	{
		return $this->hasOne(Locale_tour::class, 'id', 'ru_tour_id');
	}

	public function tour_images()
	{
		return $this->hasMany(Tour_image::class, 'tour_id', 'id');
	}
}

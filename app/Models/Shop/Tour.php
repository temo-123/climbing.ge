<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Tour extends Model
{
    use HasFactory;
    public $table = 'tours';

    protected $fillable = [
        'id',
        'url_title',
        'published',
        'min_price',
        'category_id',
    ];

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

    function user() {
		return $this->belongsToMany(User::class, 'user_tours', 'tour_id', 'user_id');
	}

	function resrtvation() {
		return $this->hasMany(Tour_reservation::class, 'tour_id', 'id');
	}
}

<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Tour extends Model
{
    // old URL keeps working (301) when url_title changes — see RecordsSlugRedirects
    use \App\Models\Concerns\RecordsSlugRedirects;

    public function slugSegments(): array { return ['tour']; }

    use HasFactory;
    public $table = 'tours';

    protected $fillable = [
        'id',
        'url_title',
        'published',
        'min_price',
        // price_currency is NOT fillable: the tour form saves its whole loaded row, which
        // would undo a currency change made in the Prices tab (TourPriceService sets it)
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

	// season + duration schedule (TourScheduleService) — these columns are NOT fillable
	// for the same reason as price_currency: the tour form saves its whole loaded row
	protected $casts = [
		'all_year' => 'boolean',
		'duration_days' => 'array',
		'start_weekdays' => 'array',
	];

	function seasons() {
		return $this->hasMany(Tour_season::class, 'tour_id', 'id')->orderBy('from_month')->orderBy('from_day');
	}

	// price calculator rules (TourPriceService)
	function prices() {
		return $this->hasMany(Tour_price::class, 'tour_id', 'id')->orderBy('days')->orderBy('persons_min');
	}

	function resrtvation() {
		return $this->hasMany(Tour_reservation::class, 'tour_id', 'id');
	}
}

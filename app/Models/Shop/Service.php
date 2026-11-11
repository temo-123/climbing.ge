<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // old URL keeps working (301) when url_title changes — see RecordsSlugRedirects
    use \App\Models\Concerns\RecordsSlugRedirects;

    public function slugSegments(): array { return ['service']; }

    // public $table = 'services';

    protected $fillable = [
        'id',
        'url_title',
        'published',
        'link_type',
        'link_id',
        'link_options',
    ];

    // Linked info shown on the service page (ServicesService::linked_info):
    //   'gear_rental'     → gear rental price list of warehouse `link_id` (null = the general one)
    //   'wall_calculator' → the climbing wall price calculator (same component as /climbing_wall_colculator)
    // New kinds: add a key to LINK_TYPES, a branch in ServicesService::linked_info(),
    // an entry in ServicesController::get_link_targets() and the frontend part on ServicePage.vue
    const LINK_TYPES = ['gear_rental', 'wall_calculator'];

    protected $casts = [
        'link_options' => 'array',
    ];

	public function us_service()
	{
		return $this->hasOne(Locale_service::class, 'id', 'us_service_id');
	}

	public function ka_service()
	{
		return $this->hasOne(Locale_service::class, 'id', 'ka_service_id');
	}

	// public function ru_service()
	// {
	// 	return $this->hasOne(Locale_service::class, 'id', 'ru_service_id');
	// }

	public function service_images()
	{
		return $this->hasMany(Service_image::class, 'service_id', 'id');
	}
}

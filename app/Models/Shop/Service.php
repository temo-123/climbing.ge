<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    // public $table = 'services';

    // protected $fillable = [
    //     'url_title',
    // ];


	public function us_service()
	{
		return $this->hasOne(Locale_service::class, 'id', 'us_service_id');
	}

	public function ka_service()
	{
		return $this->hasOne(Locale_service::class, 'id', 'ka_service_id');
	}

	public function ru_service()
	{
		return $this->hasOne(Locale_service::class, 'id', 'ru_service_id');
	}

	public function service_images()
	{
		return $this->hasMany(Service_image::class, 'service_id', 'id');
	}
}

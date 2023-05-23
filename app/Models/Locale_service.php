<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locale_service extends Model
{
    // public $table = 'locale_services';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];

	public function global_service_us()
	{
		return $this->hasOne(Service::class, 'us_service_id');
	}

	public function global_service_ka()
	{
		return $this->hasOne(Service::class, 'ka_service_id');
	}

	public function global_service_ru()
	{
		return $this->hasOne(Service::class, 'ru_service_id');
	}
}

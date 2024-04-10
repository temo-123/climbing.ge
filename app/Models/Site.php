<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Site extends Model
{
    use Notifiable;

	public function us_site()
	{
		return $this->hasOne(Locale_site::class, 'id', 'us_info_id');
	}

	public function ka_site()
	{
		return $this->hasOne(Locale_site::class, 'id', 'ka_info_id');
	}

	// public function ru_site()
	// {
	// 	return $this->hasOne(Locale_site::class, 'id', 'ru_info_id');
	// }
}

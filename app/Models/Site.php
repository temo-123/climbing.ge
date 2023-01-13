<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Site extends Model
{
    use Notifiable;

    // protected $fillable = [
    //     'title',
    //     'title_ru', 
    //     'title_ka',
    //     'text_ru', 
    //     'text_ka', 
    //     'text', 
    //     'short_description',
    //     'short_description_ru', 
    //     'short_description_ka', 
    //     'other_activity_description', 
    //     'other_activity_description_ru', 
    //     'other_activity_description_ka', 
    //     'ice_description', 
    //     'ice_description_ru', 
    //     'ice_description_ka', 
    //     'mount_description', 
    //     'mount_description_ru', 
    //     'mount_description_ka', 
    //     'outdoor_description',
    //     'outdoor_description_ru', 
    //     'outdoor_description_ka',
    //     'indoor_description', 
    //     'indoor_description_ru', 
    //     'indoor_description_ka', 
    //     'topo_description',
    //     'topo_description_ka',
    //     'topo_description_ru',
    //     'what_we_do_description',
    //     'what_we_do_description_ru',
    //     'what_we_do_description_ka',
    //     'shop_description', 
    //     'shop_description_ru', 
    //     'shop_description_ka', 
    //     'shop_short_description', 
    //     'shop_short_description_ka', 
    //     'shop_short_description_ru', 
    //     'services_description', 
    //     'services_description_ka', 
    //     'services_description_ru',

    //     'email',
    //     'fb_link', 
    //     'google_link',
    //     'twit_link', 
    //     'inst_link', 
    //     'map',     
    //     'number',     
    // ];


	public function us_site()
	{
		return $this->hasOne(Locale_site::class, 'id', 'us_site_info');
	}

	public function ka_site()
	{
		return $this->hasOne(Locale_site::class, 'id', 'ka_site_info');
	}

	public function ru_site()
	{
		return $this->hasOne(Locale_site::class, 'id', 'ru_site_info');
	}
}

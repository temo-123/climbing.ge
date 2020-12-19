<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mount extends Model
{
    public $table = 'mounts';

    protected $fillable = [
        "name",
		"name_ru",
		"name_ka",
		"short_descriptio",
		"shop_description_ru",
		"shop_descriptio_ka",	
		"text",
		"text_ru",
		"text_ka",
		"map",	
		"how_get",
		"how_get_ru",
		"how_get_ka",
		"best_time",
		"best_time_ru",
		"betst_time_kaname_ru",
		"weather",
    ];
}

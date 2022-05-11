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
		"short_description_ru",
		"short_description_ka",	

		"text",
		"text_ru",
		"text_ka",

		"how_get",
		"how_get_ru",
		"how_get_ka",

		"best_time",
		"best_time_ru",
		"best_time_ka",

		"weather",
		"map",
    ];

	public function us_mount()
	{
		return $this->hasOhe(Us_mount::class);
	}

	public function ka_mount()
	{
		return $this->hasOhe(Ka_mount::class);
	}

	public function ru_mount()
	{
		return $this->hasOhe(Ru_mount::class);
	}
}

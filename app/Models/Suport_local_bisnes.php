<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suport_local_bisnes extends Model
{
    public $table = 'suport_local_bisneses';

    use HasFactory;


	public function us_bisnes()
	{
		return $this->hasOne(Locale_bisnes::class, 'id', 'us_bisnes_id');
	}

	public function ka_bisnes()
	{
		return $this->hasOne(Locale_bisnes::class, 'id', 'ka_bisnes_id');
	}

	public function ru_bisnes()
	{
		return $this->hasOne(Locale_bisnes::class, 'id', 'ru_bisnes_id');
	}

	public function bisnes_images()
	{
		return $this->hasMany(Suport_local_bisnes_image::class, 'bisnes_id', 'id');
	}
}

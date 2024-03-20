<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_mount extends Model
{
    use HasFactory;

    public $table = 'locale_mounts';

    protected $fillable = [
        'title',
        'short_description',
        'text',
        'meta_keyword'
    ];


	public function global_mount_us()
	{
		return $this->hasOne(Mount::class, 'us_mount_id');
	}

	public function global_mount_ka()
	{
		return $this->hasOne(Mount::class, 'ka_mount_id');
	}

	public function global_mount_ru()
	{
		return $this->hasOne(Mount::class, 'ru_mount_id');
	}
}

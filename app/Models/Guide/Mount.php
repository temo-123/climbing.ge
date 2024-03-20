<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mount extends Model
{
    public $table = 'mounts';

    protected $fillable = [
        'us_mount_id',
        'ru_mount_id',
        'ka_mount_id',

		"weather",
		"map",
    ];

	public function us_mount()
	{
		return $this->hasOne(Locale_mount::class, 'id', 'us_mount_id');
	}

	public function ka_mount()
	{
		return $this->hasOne(Locale_mount::class, 'id', 'ka_mount_id');
	}

	public function ru_mount()
	{
		return $this->hasOne(Locale_mount::class, 'id', 'ru_mount_id');
	}


	public function articles()
	{
		// return $this->belongsToMany(Article::class,);
        return $this->belongsToMany(Article::class, 'article_mount', 'mount_id', 'article_id')->where('published', '=', 1);
	}
}

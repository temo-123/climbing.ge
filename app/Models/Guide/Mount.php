<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Mount extends Model
{
    public $table = 'mounts';

    // Real PHP property (not an Eloquent attribute) so setting it doesn't get
    // picked up by getDirty()/the UPDATE query. Read by MountObserver::updated()
    // to decide whether/how the admin opted in to a notification for this save:
    // 'none' (default), 'update', or 'new' (re-announce as if newly published).
    public $notifyMode = 'none';

    protected $fillable = [
        'us_mount_id',
        // 'ru_mount_id',
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

	// public function ru_mount()
	// {
	// 	return $this->hasOne(Locale_mount::class, 'id', 'ru_mount_id');
	// }

	public function articles()
	{
        return $this->belongsToMany(Article::class, 'article_mount', 'mount_id', 'article_id');
	}
}

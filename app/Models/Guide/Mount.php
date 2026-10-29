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

    // published: 1 = public, 2 = public by URL only, 0 = hidden. Mounts created
    // before the form set the column have NULL — they were always public, so
    // NULL counts as published (otherwise the mountaineering list loses its
    // mountain filter and grouping, and route pages their massif block).
    public function scopeListed($query)
    {
        return $query->where(fn ($q) => $q->where('published', 1)->orWhereNull('published'));
    }

    // can be opened (listed, or public by URL)
    public function scopeViewable($query)
    {
        return $query->where(fn ($q) => $q->whereIn('published', [1, 2])->orWhereNull('published'));
    }
}

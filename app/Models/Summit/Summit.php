<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Article;
use App\Models\Guide\Mount;

class Summit extends Model
{
    use HasFactory;

    // Real PHP property (not an Eloquent attribute) so setting it doesn't get
    // picked up by getDirty()/the UPDATE query. Read by SummitObserver::updated()
    // to decide whether/how the admin opted in to a notification for this save:
    // 'none' (default), 'update', or 'new' (re-announce as if newly published).
    public $notifyMode = 'none';

    protected $fillable = [
        'title', 'ka_title',
        'url_title',
        'description', 'ka_description',
        'image',
        'height',
        'latitude', 'longitude',
        'mount_id',
        'qr_code',
        'published',
    ];

    protected $casts = [
        'published'  => 'integer',
        'latitude'   => 'float',
        'longitude'  => 'float',
        'height'     => 'integer',
    ];

    public function mount()
    {
        return $this->belongsTo(Mount::class, 'mount_id');
    }

    public function mountRoutes()
    {
        return $this->hasMany(SummitMountRoute::class, 'summit_id');
    }

    public function mountRouteArticles()
    {
        return $this->hasManyThrough(Article::class, SummitMountRoute::class, 'summit_id', 'id', 'id', 'article_id');
    }

    public function ascents()
    {
        return $this->hasMany(SummitAscent::class, 'summit_id');
    }
}

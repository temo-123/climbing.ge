<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Region;
use App\Models\Guide\Article;

class Summit extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'ka_title',
        'url_title',
        'description', 'ka_description',
        'image',
        'height',
        'latitude', 'longitude',
        'region_id', 'mount_route_id',
        'qr_code',
        'published',
    ];

    protected $casts = [
        'published'  => 'boolean',
        'latitude'   => 'float',
        'longitude'  => 'float',
        'height'     => 'integer',
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_id');
    }

    public function mountRoute()
    {
        return $this->belongsTo(Article::class, 'mount_route_id');
    }

    public function ascents()
    {
        return $this->hasMany(SummitAscent::class, 'summit_id');
    }
}

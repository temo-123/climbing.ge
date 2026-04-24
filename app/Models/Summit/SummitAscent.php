<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guide\Route;

class SummitAscent extends Model
{
    use HasFactory;

    protected $fillable = [
        'summit_id',
        'name',
        'surname',
        'email',
        'route_id',
        'other_route',
        'comment',
        'photo',
        'is_gps_validated',
        'user_latitude',
        'user_longitude',
        'ascent_date',
    ];

    protected $casts = [
        'is_gps_validated' => 'boolean',
        'user_latitude'    => 'float',
        'user_longitude'   => 'float',
        'ascent_date'      => 'date',
    ];

    public function summit()
    {
        return $this->belongsTo(Summit::class, 'summit_id');
    }

    public function users()
    {
        return $this->hasMany(SummitAscentUser::class, 'ascent_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function ascentRoutes()
    {
        return $this->hasMany(SummitAscentRoute::class, 'ascent_id');
    }
}

<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class RouteAscent extends Model
{
    use HasFactory;

    // onsight / flash / redpoint = clean lead sends, toprope = not led
    const STYLES = ['onsight', 'flash', 'redpoint', 'toprope'];

    protected $fillable = [
        'route_id',
        'user_id',
        'ascent_date',
        'ascent_style',
        'attempts',
        'suggested_grade',
        'comment',
        'photo',
    ];

    protected $casts = [
        'ascent_date' => 'date:Y-m-d',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

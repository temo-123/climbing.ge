<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClimbingRoutesJson extends Model
{
    use HasFactory;

    public $table = 'climbing_routes_jsons';

    protected $fillable = [
        'route_id',
        'json',
        'canvas_width',
        'canvas_height',
        'bg_left',
        'bg_top',
        'bg_width',
        'bg_height',
        'sector_image_id',
        'created_at',
        'updated_at'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

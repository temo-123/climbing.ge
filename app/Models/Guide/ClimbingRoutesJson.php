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
        'sector_image_id',
        'created_at',
        'updated_at'
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}

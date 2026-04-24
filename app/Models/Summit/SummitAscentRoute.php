<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummitAscentRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'ascent_id',
        'route_id',
        'other_route_name',
    ];

    public function ascent()
    {
        return $this->belongsTo(SummitAscent::class, 'ascent_id');
    }
}

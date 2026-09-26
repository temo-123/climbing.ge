<?php

namespace App\Models;

use App\Services\CoefficientService;
use Illuminate\Database\Eloquent\Model;

class Coefficient extends Model
{
    protected $fillable = ['slug', 'value', 'description'];

    protected $casts = [
        'value' => 'float',
    ];

    protected static function booted(): void
    {
        // Every page load reads the cached coefficient map, so any write
        // must drop it or the change won't show up until the cache expires.
        static::saved(fn () => CoefficientService::forget());
        static::deleted(fn () => CoefficientService::forget());
    }
}

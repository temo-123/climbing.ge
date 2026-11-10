<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

/**
 * One price rule of a tour: `days` long, for `persons_min`–`persons_max` people
 * (max NULL = and more), `price` per person or per group. See TourPriceService.
 */
class Tour_price extends Model
{
    protected $table = 'tour_prices';

    protected $fillable = ['tour_id', 'days', 'persons_min', 'persons_max', 'price', 'price_type'];

    protected $casts = [
        'days' => 'integer',
        'persons_min' => 'integer',
        'persons_max' => 'integer',
        'price' => 'float',
    ];
}

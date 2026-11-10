<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

/**
 * A yearly period when the tour runs (month/day → month/day, repeats every year;
 * from > to wraps the new year, e.g. 1 Dec → 15 Mar). See TourScheduleService.
 */
class Tour_season extends Model
{
    protected $table = 'tour_seasons';

    protected $fillable = ['tour_id', 'from_month', 'from_day', 'to_month', 'to_day'];

    protected $casts = [
        'from_month' => 'integer',
        'from_day' => 'integer',
        'to_month' => 'integer',
        'to_day' => 'integer',
    ];
}

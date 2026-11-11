<?php

namespace App\Models\Shop\Rental;

use Illuminate\Database\Eloquent\Model;

class RentalGearPrice extends Model
{
    protected $table = 'rental_gear_prices';

    protected $fillable = ['rental_gear_id', 'days', 'price'];

    protected $casts = ['days' => 'integer', 'price' => 'float'];
}

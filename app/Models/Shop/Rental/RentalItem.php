<?php

namespace App\Models\Shop\Rental;

use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    protected $table = 'rental_items';

    protected $fillable = ['rental_id', 'rental_gear_id', 'gear_name', 'quantity', 'unit_price', 'line_total'];

    protected $casts = ['quantity' => 'integer', 'unit_price' => 'float', 'line_total' => 'float'];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }

    public function gear()
    {
        return $this->belongsTo(RentalGear::class, 'rental_gear_id');
    }
}

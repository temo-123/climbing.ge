<?php

namespace App\Models\Shop\Rental;

use Illuminate\Database\Eloquent\Model;

// Where rental gear is kept. One `general` warehouse = the default (docs/FUNCTIONS_DOCS/shop/gear_rental.md)
class RentalWarehouse extends Model
{
    protected $table = 'rental_warehouses';

    protected $fillable = ['name', 'general', 'address', 'note'];

    protected $casts = ['general' => 'boolean'];

    public function gear()
    {
        return $this->belongsToMany(RentalGear::class, 'rental_warehouse_gear', 'rental_warehouse_id', 'rental_gear_id')
            ->withPivot('quantity')->withTimestamps();
    }

    public function rentals()
    {
        return $this->hasMany(Rental::class, 'rental_warehouse_id');
    }

    public static function general(): ?self
    {
        return static::where('general', true)->orderBy('id')->first() ?? static::orderBy('id')->first();
    }
}

<?php

namespace App\Models\Shop\Rental;

use Illuminate\Database\Eloquent\Model;

// One kind of rental gear (e.g. "Harness", "Ice axe") with prices per rental period
class RentalGear extends Model
{
    protected $table = 'rental_gear';

    protected $fillable = ['us_name', 'ka_name', 'category', 'code', 'deposit', 'show_in_shop', 'sort_order', 'us_description', 'ka_description'];

    protected $casts = [
        'show_in_shop' => 'boolean',
        'deposit' => 'float',
        'sort_order' => 'integer',
    ];

    public function prices()
    {
        return $this->hasMany(RentalGearPrice::class, 'rental_gear_id')->orderBy('days');
    }

    public function warehouses()
    {
        return $this->belongsToMany(RentalWarehouse::class, 'rental_warehouse_gear', 'rental_gear_id', 'rental_warehouse_id')
            ->withPivot('quantity')->withTimestamps();
    }

    public function items()
    {
        return $this->hasMany(RentalItem::class, 'rental_gear_id');
    }

    public function name_for(string $lang): string
    {
        return ($lang === 'ka' && $this->ka_name) ? $this->ka_name : $this->us_name;
    }

    // short description for the gear lists; Georgian falls back to English
    public function description_for(string $lang): ?string
    {
        return ($lang === 'ka' && $this->ka_description) ? $this->ka_description : $this->us_description;
    }
}

<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class CustomOrderAddress extends Model
{
    protected $table = 'custom_order_addresses';

    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'map',
        'region_id',
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'custom_order_addresses_order', 'custom_order_address_id', 'order_id');
    }

    public function region()
    {
        return $this->belongsTo(Shiped_region::class, 'region_id');
    }
}

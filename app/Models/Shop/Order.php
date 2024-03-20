<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'confirm',
        'adres_id',
        'order_status',
        'order_payment',

        'treatment',
        'preparation_for_shipment',
        'ready_to_ship',
        'order_has_been_sent',
        'transferred_to_the_delivery_service',
        'delivered',

        'treatment_data',
        'preparation_for_shipment_data',
        'ready_to_ship_data',
        'order_has_been_sent_data',
        'transferred_to_the_delivery_service_data',
        'delivered_data',
	];
}

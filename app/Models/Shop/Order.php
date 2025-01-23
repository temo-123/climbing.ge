<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'confirm',
        'adres_id',
        // 'order_status',
        'order_payment',

        'status',
        'status_updating_data',
	];
}

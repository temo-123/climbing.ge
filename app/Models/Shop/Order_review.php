<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

/** Order (delivery / service) review — admin only. See docs/FUNCTIONS_DOCS/shop/order_review.md */
class Order_review extends Model
{
    protected $table = 'order_reviews';

    protected $fillable = ['order_id', 'user_id', 'stars', 'delivery_speed', 'packaging', 'text'];

    protected $casts = [
        'stars' => 'integer',
        'delivery_speed' => 'integer',
        'packaging' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}

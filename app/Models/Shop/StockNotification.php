<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class StockNotification extends Model
{
    protected $fillable = [
        'option_id',
        'user_id',
        'locale',
        'notified_at',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
    ];

    public function option()
    {
        return $this->belongsTo(Product_option::class, 'option_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

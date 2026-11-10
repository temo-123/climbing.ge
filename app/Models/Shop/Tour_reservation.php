<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in',
        'persons',
        'days',
        'price_total',
        'price_currency',
        'price_details',
        'name',
        'email',
        'phone',
        'country',
        'city',
        'text',
        'tour_id',
        'verificate',
        'verified_at',
        'verify_method',
        'verify_sent_at',
        'verify_sent_count',
        'confirm',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'price_details' => 'array',
        'price_total' => 'float',
        'verify_sent_at' => 'datetime',
    ];

    // reserver accounts (pivot tour_reservations_users)
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'tour_reservations_users', 'reservation_id', 'user_id');
    }
}

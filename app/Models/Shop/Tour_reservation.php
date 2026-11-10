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
        'tour_language',
        'completed_at',
        'commission_percent',
        'commission_amount',
        'commission_paid_at',
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
        'guide_id',
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
        'completed_at' => 'datetime',
        'commission_paid_at' => 'datetime',
        'commission_percent' => 'float',
        'commission_amount' => 'float',
        'price_total' => 'float',
        'verify_sent_at' => 'datetime',
    ];

    // guide in charge (one of the tour's guides — TourGuideService)
    public function guide()
    {
        return $this->belongsTo(\App\Models\User::class, 'guide_id');
    }

    // reserver accounts (pivot tour_reservations_users)
    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'tour_reservations_users', 'reservation_id', 'user_id');
    }
}

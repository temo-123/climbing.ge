<?php

namespace App\Models\Shop\Rental;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

// One rental: renter, ID card photo (private disk), period, money, return
class Rental extends Model
{
    const ACTIVE = 'active';
    const RETURNED = 'returned';
    const CANCELLED = 'cancelled';

    protected $table = 'rentals';

    protected $fillable = [
        'rental_warehouse_id', 'user_id', 'name', 'surname', 'email', 'phone', 'id_number',
        'start_at', 'days', 'due_at', 'status', 'subtotal', 'discount', 'total',
        'deposit', 'deposit_returned', 'notes',
    ];

    // the photo path never leaves the server — the panel loads it from set_gear_rental/id_card/{id}
    protected $hidden = ['id_card_image'];

    protected $appends = ['has_id_card', 'is_overdue'];

    protected $casts = [
        'start_at' => 'datetime',
        'due_at' => 'datetime',
        'returned_at' => 'datetime',
        'paid_at' => 'datetime',
        'days' => 'integer',
        'subtotal' => 'float',
        'discount' => 'float',
        'total' => 'float',
        'deposit' => 'float',
        'deposit_returned' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(RentalItem::class, 'rental_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(RentalWarehouse::class, 'rental_warehouse_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getHasIdCardAttribute(): bool
    {
        return !empty($this->attributes['id_card_image'] ?? null);
    }

    public function getIsOverdueAttribute(): bool
    {
        return ($this->attributes['status'] ?? null) === self::ACTIVE && $this->due_at && $this->due_at->isPast();
    }

    public function scopeActive($q)
    {
        return $q->where('status', self::ACTIVE);
    }
}

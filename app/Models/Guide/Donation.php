<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'country',
        'age',
        'email',
        'phone_number',
        'comment',
        'amount',
        'currency',
        'status',
        'flitt_order_id',
        'response_url',
        'server_callback_url',
        'user_id',
        'tbc_pay_id',
        'tbc_pay_status',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_PAID = 'paid';
    public const STATUS_FAILED = 'failed';
    public const STATUS_EXPIRED = 'expired';
    public const STATUS_DECLINED = 'declined';

    /**
     * Convert amount to cents for Flitt payment
     */
    public function getAmountInCents(): int
    {
        return (int) ($this->amount * 100);
    }

    /**
     * Check if payment is approved
     */
    public function isPaid(): bool
    {
        return $this->status === self::STATUS_PAID;
    }

    /**
     * Check if payment is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if payment is failed
     */
    public function isFailed(): bool
    {
        return in_array($this->status, [self::STATUS_FAILED, self::STATUS_EXPIRED, self::STATUS_DECLINED]);
    }

    /**
     * Check if payment is declined
     */
    public function isDeclined(): bool
    {
        return $this->status === self::STATUS_DECLINED;
    }

    /**
     * Check if payment is expired
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED;
    }

    /**
     * Check if payment is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }
}


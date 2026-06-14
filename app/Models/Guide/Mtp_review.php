<?php

namespace App\Models\Guide;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Mtp_review extends Model
{
    protected $table = 'mtp_reviews';

    protected $fillable = ['stars', 'text', 'mtp_id'];

    public function mtp()
    {
        return $this->hasOne(Mtp::class, 'id', 'mtp_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

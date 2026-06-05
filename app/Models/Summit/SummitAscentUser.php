<?php

namespace App\Models\Summit;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class SummitAscentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'ascent_id',
        'user_id',
    ];

    public function ascent()
    {
        return $this->belongsTo(SummitAscent::class, 'ascent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

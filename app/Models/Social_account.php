<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social_account extends Model
{
    protected $fillable = ['provider', 'provider_user_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

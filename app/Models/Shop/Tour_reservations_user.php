<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour_reservations_user extends Model
{
    use HasFactory;

    // function user() {
    //   return $this->belongsToMany(Tour_reservation::class, 'Tour_reservation_users', 'comment_id', 'answer_id');
    // }
}

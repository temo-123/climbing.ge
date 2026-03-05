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
        'email',
        'phone',
        'country',
        'city',
        'text',
        'tour_id',
	];
}

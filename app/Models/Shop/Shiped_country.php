<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shiped_country extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'shiping_price',
        'free_shiping_price_after',
	];
}

<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shiped_region extends Model
{
    use HasFactory;

    protected $fillable = [
        'region',
        'shiping_price',
        'free_shiping_price_after',
	];
}

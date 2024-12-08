<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description'
    ];
}

<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locale_tour extends Model
{
    use HasFactory;

    // public $table = 'locale_tours';
    protected $fillable = [
        'id',
        'locale',
        'title',
        'short_description',
        'text',
        'location',
        'duration',
    ];

    public function global_tour_us()
    {
        return $this->hasOne(Tour::class, 'us_tour_id');
    }

    public function global_tour_ka()
    {
        return $this->hasOne(Tour::class, 'ka_tour_id');
    }
}

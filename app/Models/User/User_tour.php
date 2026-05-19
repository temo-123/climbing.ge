<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Shop\Tour;
use App\Models\User;

class User_tour extends Model
{
    use HasFactory;

    protected $table = 'user_tours';

    protected $fillable = ['tour_id', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function tour()
    {
        return $this->hasOne(Tour::class, 'id', 'tour_id');
    }
}

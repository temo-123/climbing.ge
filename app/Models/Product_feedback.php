<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_feedback extends Model
{
    use HasFactory;

    public function product()
    {
      return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'product_feedback_user', 'feedback_id', 'user_id');
    }
}

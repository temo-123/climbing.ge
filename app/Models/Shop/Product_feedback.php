<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Product_feedback extends Model
{
    use HasFactory;

    public function product()
    {
      return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_product_feedbacks', 'feedback_id', 'user_id');
    }
}

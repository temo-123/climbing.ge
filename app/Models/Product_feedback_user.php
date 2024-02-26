<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_feedback_user extends Model
{
    use HasFactory;

    public $table = 'product_feedback_user';

    // public function product()
    // {
	// 	return $this->hasOne(Product::class, 'id');
    // }

    // public function user()
    // {
    //     dd('dd');
    //     return $this->belongsToMany(User::class, 'product_feedback_user', 'feedback_id', 'user_id');
    // }
}

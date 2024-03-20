<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Guide\Comment;
use App\Models\Shop\Product_feedback;

class Non_registered_commenter extends Model
{
    use HasFactory;

    public function article_comments()
    {
		return $this->hasMany(Comment::class, 'email', 'email');
    }
	public function product_feedbacks()
	{
		return $this->hasMany(Product_feedback::class, 'email', 'email');
	}
}

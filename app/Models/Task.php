<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

	public function user()
	{
		return $this->hasOne(User::class, 'id', 'user_id');
	}

	public function category()
	{
		return $this->hasOne(Task_category::class, 'id', 'category_id');
	}
}

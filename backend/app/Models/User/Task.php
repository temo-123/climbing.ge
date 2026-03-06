<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Shop\Order;
use App\Models\Shop\Product_option;

class Task extends Model
{
    use HasFactory;

	public function from_user()
	{
		return $this->belongsTo(User::class, 'from_user_id');
	}

	public function for_user()
	{
		return $this->belongsTo(User::class, 'for_user_id');
	}

	public function orders()
	{
		return $this->belongsToMany(Order::class, 'order_tasks');
	}

	public function product_options()
	{
		return $this->belongsToMany(Product_option::class, 'product_option_tasks');
	}
}

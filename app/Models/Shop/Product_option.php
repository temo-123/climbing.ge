<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

use App\Models\User\Warehouse;

class Product_option extends Model
{
    use HasFactory;

    protected $fillable = [
	    'published',
	    'discount',
	];

    /**
     * Boot the model.
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function ($option) {
            // Detach from warehouses
            $option->warehouse()->detach();

            // Delete related order_products records
            \App\Models\Shop\Order_products::where('product_option_id', $option->id)->delete();

            // Delete related cart records
            \App\Models\Shop\Cart::where('option_id', $option->id)->delete();
        });
    }


	public function images()
	{
		return $this->hasMany(Option_image::class, 'option_id');
	}

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_product_options', 'option_id', 'order_id')->withPivot('quantity', 'price', 'currency');
    }

    public function cart_items()
    {
        return $this->belongsToMany(Cart_item::class, 'cart_item_product_options', 'option_id', 'cart_item_id')->withPivot('quantity', 'price', 'currency');   
    }

    public function warehouse()
    {
        return $this->belongsToMany(Warehouse::class, 'warehouses_product_options', 'product_option_id', 'warehouse_id')->withPivot('quantity');
    }
}

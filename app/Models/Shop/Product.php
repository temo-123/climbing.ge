<?php

namespace App\Models\Shop;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Product extends Model
{
    // old URL keeps working (301) when url_title changes — see RecordsSlugRedirects
    use \App\Models\Concerns\RecordsSlugRedirects;

    public function slugSegments(): array { return ['product']; }

    /**
     * Public URLs of the product's photos, main photo first: the cheapest option that has
     * photos, then the other options'. Photos live in option_images
     * (public/images/product_option_img/) — product_images is unused, so SEO / share
     * previews / the Merchant feed showed the generic default image for every product.
     * File names contain "{…}", hence rawurlencode().
     */
    public function photo_urls(int $limit = 10): array
    {
        return \Illuminate\Support\Facades\DB::table('option_images as i')
            ->join('product_options as o', 'o.id', '=', 'i.option_id')
            ->where('o.product_id', $this->id)
            ->orderBy('o.price')->orderBy('o.id')->orderBy('i.id')
            ->limit($limit)
            ->pluck('i.image')
            ->filter()
            ->map(fn ($file) => asset('images/product_option_img/' . rawurlencode($file)))
            ->values()
            ->all();
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($product) {
            // Delete related favorite_products before deleting the product
            \App\Models\Shop\Favorite_product::where('product_id', $product->id)->delete();
        });
    }

    public $table = 'products';

    protected $fillable = [
	    'id',
	    'published',
      'url_title',

      // 'discount',
      // 'material',

      'made_in_georgia',

      'sale_type',
      'condition',

      'subcategory_id',
      'brand_id',
      'equipment_type',

      'us_product_id',
      'ka_product_id'
      // 'ru_product_id'
	];

	public function us_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'us_product_id');
	}

	public function ka_product()
	{
		return $this->hasOne(Locale_product::class, 'id', 'ka_product_id');
	}

	// public function ru_product()
	// {
	// 	return $this->hasOne(Locale_product::class, 'id', 'ru_product_id');
	// }

  public function product_subcategory()
  {
    return $this->hasOne(Product_subcategory::class, 'id', 'subcategory_id');
  }

  public function brand()
  {
    return $this->belongsTo(Product_brand::class, 'brand_id');
  }

  public function product_options()
  {
    return $this->hasMany(Product_option::class, 'product_id');
  }

  public function feedbacks()
  {
    return $this->hasMany(Product_feedback::class, 'product_id', 'id');
  }

  public function orders()
  {
        return $this->belongsToMany(Order::class, 'order_products', 'order_id', 'product_id');
  }

  public function user()
  {
        return $this->belongsToMany(User::class, 'user_products', 'product_id', 'user_id');
  }

}

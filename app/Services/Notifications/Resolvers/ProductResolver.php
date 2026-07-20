<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use App\Models\Shop\Favorite_product;
use Illuminate\Database\Eloquent\Model;

class ProductResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_product';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'product_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_product = $locale === 'ka' ? $model->ka_product : $model->us_product;
        return $locale_product->title ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.shop_url') . '/product/' . $model->url_title;
    }

    public function favoriteModelClass(): ?string
    {
        return Favorite_product::class;
    }

    public function favoriteForeignKey(): ?string
    {
        return 'product_id';
    }
}

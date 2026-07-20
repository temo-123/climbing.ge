<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class TourResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_tour';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'tour_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_tour = $locale === 'ka' ? $model->ka_tour : $model->us_tour;
        return $locale_tour->title ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.shop_url') . '/tour/' . $model->url_title;
    }

    public function favoriteModelClass(): ?string
    {
        return null;
    }

    public function favoriteForeignKey(): ?string
    {
        return null;
    }
}

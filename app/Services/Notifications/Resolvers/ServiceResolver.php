<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class ServiceResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_service';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'service_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_service = $locale === 'ka' ? $model->ka_service : $model->us_service;
        return $locale_service->title ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.shop_url') . '/service/' . $model->url_title;
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

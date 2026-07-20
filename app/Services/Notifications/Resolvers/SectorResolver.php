<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class SectorResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_sector';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'sector_updated';
    }

    public function title(Model $model, string $locale): string
    {
        return $model->name ?? '';
    }

    public function url(Model $model): string
    {
        $article = $model->article;
        return config('app.base_url_ssh') . '/outdoor/' . ($article->url_title ?? '');
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

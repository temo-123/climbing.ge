<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class SummitResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_summit';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'summit_updated';
    }

    public function title(Model $model, string $locale): string
    {
        return ($locale === 'ka' ? $model->ka_title : $model->title) ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.summit_url') . '/summit/' . $model->url_title;
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

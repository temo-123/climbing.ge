<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use App\Models\Films\Favorite_film;
use Illuminate\Database\Eloquent\Model;

class FilmResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_film';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'film_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_film = $locale === 'ka' ? $model->ka_film : $model->us_film;
        return $locale_film->title ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.films_url') . '/film/' . $model->url_title;
    }

    public function favoriteModelClass(): ?string
    {
        return Favorite_film::class;
    }

    public function favoriteForeignKey(): ?string
    {
        return 'film_id';
    }
}

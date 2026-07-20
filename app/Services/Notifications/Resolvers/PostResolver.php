<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class PostResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_blog_post';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'blog_post_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_post = $locale === 'ka' ? $model->ka_post : $model->us_post;
        return $locale_post->title ?? '';
    }

    public function url(Model $model): string
    {
        return 'https://' . config('app.blog_url') . '/post/' . $model->url_title;
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

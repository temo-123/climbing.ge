<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use Illuminate\Database\Eloquent\Model;

class MountResolver implements ContentResolverInterface
{
    public function preferenceKey(Model $model): ?string
    {
        return 'add_new_mount';
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return 'mount_updated';
    }

    public function title(Model $model, string $locale): string
    {
        $locale_mount = $locale === 'ka' ? $model->ka_mount : $model->us_mount;
        return $locale_mount->title ?? '';
    }

    public function url(Model $model): string
    {
        return config('app.base_url_ssh') . '/mountaineering/' . $model->id;
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

<?php

namespace App\Services\Notifications\Resolvers;

use App\Services\Notifications\ContentResolverInterface;
use App\Models\Guide\Favorite_outdoor_area;
use Illuminate\Database\Eloquent\Model;

class ArticleResolver implements ContentResolverInterface
{
    private const CATEGORY_PREFERENCE_KEYS = [
        'outdoor'  => 'add_new_outdoor_spot',
        'ice'      => 'add_new_ice_spot',
        'indoor'   => 'add_new_gym',
        'tech_tip' => 'add_new_techtip',
        'news'     => 'news',
    ];

    private const CATEGORY_URL_SEGMENTS = [
        'outdoor'  => 'outdoor',
        'ice'      => 'ice',
        'indoor'   => 'indoor',
        'tech_tip' => 'tech_tip',
        'news'     => 'news',
    ];

    private const CATEGORY_UPDATE_PREFERENCE_KEYS = [
        'outdoor'  => 'outdoor_spot_updated',
        'ice'      => 'ice_spot_updated',
        'indoor'   => 'gym_updated',
        'tech_tip' => 'techtip_updated',
        'news'     => 'news_updated',
    ];

    public function preferenceKey(Model $model): ?string
    {
        return self::CATEGORY_PREFERENCE_KEYS[$model->category] ?? null;
    }

    public function updatePreferenceKey(Model $model): ?string
    {
        return self::CATEGORY_UPDATE_PREFERENCE_KEYS[$model->category] ?? null;
    }

    public function title(Model $model, string $locale): string
    {
        $locale_article = $locale === 'ka' ? $model->global_article_ka : $model->global_article_us;
        return $locale_article->title ?? '';
    }

    public function url(Model $model): string
    {
        $segment = self::CATEGORY_URL_SEGMENTS[$model->category] ?? 'outdoor';
        return config('app.base_url_ssh') . '/' . $segment . '/' . $model->url_title;
    }

    public function favoriteModelClass(): ?string
    {
        return Favorite_outdoor_area::class;
    }

    public function favoriteForeignKey(): ?string
    {
        return 'article_id';
    }
}

<?php

namespace App\Services;

use App\Models\Blog\Post;
use App\Models\Blog\Locale_post;

use App\Services\Abstract\LocaleContentService;

class PostService extends LocaleContentService
{
    public static function get_locale_posts_use_locale($global_posts, $locale = 'us')
    {
        return (new static)->get_locale_content_use_locale(
            collect($global_posts),
            Locale_post::class,
            '_post_id',
            $locale
        );
    }
}
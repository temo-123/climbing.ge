<?php

namespace App\Models\Concerns;

use App\Services\UrlRedirectService;

/**
 * When a record's url_title changes (any save: admin "change URL title", summit / event
 * forms…), its old public URL keeps working: a 301 redirect is recorded for every URL
 * segment the record is shown under (see slugSegments()).
 */
trait RecordsSlugRedirects
{
    public static function bootRecordsSlugRedirects(): void
    {
        static::updated(function ($model) {
            if ($model->wasChanged('url_title')) {
                UrlRedirectService::record($model->slugSegments(), $model->getOriginal('url_title'), $model->url_title);
            }
        });
    }

    /** URL path segments this record lives under, e.g. ['outdoor'] or ['news']. */
    abstract public function slugSegments(): array;
}

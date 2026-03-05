<?php

namespace App\Services\Abstract;

use App\Models\Locale_site;
use Illuminate\Support\Collection;

class LocaleSiteService
{
    /**
     * Get all locale site data.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllLocaleData()
    {
        return Locale_site::all();
    }

    /**
     * Get locale data by slug.
     *
     * @param string $slug
     * @return \App\Models\Locale_site|null
     */
    public static function getBySlug($slug)
    {
        return Locale_site::where('slug', $slug)->first();
    }

    /**
     * Get data for a specific locale by slug.
     *
     * @param string $slug
     * @param string $locale
     * @return string|null
     */
    public static function getDataBySlugAndLocale($slug, $locale)
    {
        return Locale_site::getDataBySlug($slug, $locale);
    }

    /**
     * Update data for a specific locale by slug.
     *
     * @param string $slug
     * @param string $locale
     * @param string $data
     * @return bool
     */
    public static function updateDataBySlugAndLocale($slug, $locale, $data)
    {
        return Locale_site::updateDataBySlug($slug, $locale, $data);
    }

    /**
     * Create or update locale data by slug.
     *
     * @param string $slug
     * @param array $data
     * @return \App\Models\Locale_site
     */
    public static function createOrUpdateBySlug($slug, $data)
    {
        return Locale_site::createOrUpdate($slug, $data);
    }

    /**
     * Get all slugs.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getAllSlugs()
    {
        return Locale_site::pluck('slug');
    }

    /**
     * Get locale data grouped by locale.
     *
     * @return array
     */
    public static function getGroupedByLocale()
    {
        $data = Locale_site::all();
        $grouped = [];

        foreach ($data as $item) {
            $grouped['ka'][$item->slug] = $item->ka_data;
            $grouped['us'][$item->slug] = $item->us_data;
        }

        return $grouped;
    }

    /**
     * Bulk update locale data.
     *
     * @param array $data
     * @return void
     */
    public static function bulkUpdate($data)
    {
        foreach ($data as $slug => $values) {
            self::createOrUpdateBySlug($slug, $values);
        }
    }

    /**
     * Delete locale data by slug.
     *
     * @param string $slug
     * @return bool
     */
    public static function deleteBySlug($slug)
    {
        $record = self::getBySlug($slug);
        if ($record) {
            $record->delete();
            return true;
        }
        return false;
    }
}

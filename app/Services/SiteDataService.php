<?php

namespace App\Services;

use App\Models\Site;
use App\Models\Locale_site;
use App\Models\Site_social_link;
// use App\Services\Abstract\LocaleContentService;
use App\Services\Abstract\LocaleSiteService;

class SiteDataService extends LocaleSiteService
{
    /**
     * Get localized site data for a given locale.
     *
     * @param string $locale
     * @return array|null
     */
    public static function getSiteData($locale = 'us')
    {
        $localeData = (new static)-> getGroupedByLocale();
        
        return [
            'global_data' => Site::first(),
            'locale_data' => isset($localeData[$locale]) ? $localeData[$locale] : (isset($localeData['us']) ? $localeData['us'] : null),
        ];
    }

    /**
     * Get all social links for the site.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getSocialLinks()
    {
        return Site_social_link::all();
    }

    /**
     * Update site data for a specific locale.
     *
     * @param array $data
     * @param string $locale
     * @return bool
     */
    public static function updateSiteData($data, $locale = 'us')
    {
        $site = Site::first();
        if (!$site) {
            return false;
        }

        // Update global data if provided
        if (isset($data['global_data'])) {
            $site->update($data['global_data']);
        }

        // Update locale data
        if (isset($data['locale_data'])) {
            LocaleSiteService::bulkUpdate([$locale => $data['locale_data']]);
        }

        return true;
    }

    /**
     * Add a new social link.
     *
     * @param array $data
     * @return \App\Models\Site_social_link
     */
    public static function addSocialLink($data)
    {
        return Site_social_link::create($data);
    }

    /**
     * Update an existing social link.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public static function updateSocialLink($id, $data)
    {
        $link = Site_social_link::find($id);
        if ($link) {
            $link->update($data);
            return true;
        }
        return false;
    }

    /**
     * Delete a social link.
     *
     * @param int $id
     * @return bool
     */
    public static function deleteSocialLink($id)
    {
        $link = Site_social_link::find($id);
        if ($link) {
            $link->delete();
            return true;
        }
        return false;
    }
}

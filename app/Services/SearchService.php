<?php

namespace App\Services;

use App\Models\Shop\Locale_tour;
use App\Models\Shop\Tour;
use App\Models\Shop\Tour_image;
use App\Models\User\User_tour;

use Carbon\Carbon;

use App\Services\Abstract\LocaleContentService;

class SearchService extends LocaleContentService {
    public static function universalSearch($model, $service, $service_function, $type, $global_item, $query, $locale = 'us')
    {
            $locale_items = $model::
                when($query, function ($q, $search) {
                    $q->where('title', 'like', "%$search%")
                      ->orWhere('short_description', 'like', "%$search%");
                })->
                get();
            $locale_items = $locale_items->where('locale', '=', $locale);

            if ($locale_items->count() != 0) {
                $searching_global_items = [];

                foreach ($locale_items as $locale_item) {

// dd($locale_item->$global_item->published == 1);
                    if ($locale_item->$global_item->published == 1) {
                        array_push($searching_global_items, $locale_item->$global_item);
                    }
                }

                return $service::$service_function($searching_global_items, $locale);
            }

        return [];
    }
}

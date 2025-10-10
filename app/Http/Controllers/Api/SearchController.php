<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// for article searching
use App\Models\Guide\Article;
use App\Models\Guide\Locale_article;

// for product searching
use App\Models\Shop\Product;
use App\Models\Shop\Locale_product;

use App\Services\ArticlesService;
use App\Services\ProductService;

use App\Services\SearchService;

use LanguageDetection\Language;

class SearchController extends Controller
{
    private $detector;

    public function __construct(Language $detector)
    {
        $this->detector = $detector;
    }

    public function productSearch(Request $request)
    {
        if (!$this->check_leng($request->query_request)) {
            return response()->json(['error' => 'Language error'], 400);
        }
        $locale = 'us';
        return SearchService::universalSearch(Locale_product::class,  ProductService::class, 'get_locale_product_use_locale', 'product', 'global_product_'.$locale, $request->query_request, $locale);
    }

    public function articleSearch(Request $request)
    {
        if (!$this->check_leng($request->query_request)) {
            return response()->json(['error' => 'Language error'], 400);
        }
        $locale = 'us';
        return SearchService::universalSearch(Locale_article::class, ArticlesService::class, 'get_locale_article_use_locale', 'article', 'global_article_'.$locale, $request->query_request, $locale);
    }

    function check_leng($text) {
        $language = (string) $this->detector->detect($text);
        if ($language === 'ka' || $language === 'en') {
            return true;
        } else {
            return false;
        }
    }
}

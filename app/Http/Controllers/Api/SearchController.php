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

// for film searching
use App\Models\Films\Film;
use App\Models\Films\Locale_film;

// use LanguageDetector\LanguageDetector;

use App\Services\ProductService;
use App\Services\ArticlesService;

class SearchController extends Controller
{
    private $detector;

    // public function __construct(LanguageDetector $detector){
    //     $this->detector = $detector;
    // }

    public function productSearch(Request $request)
    {
        $locale_products = Locale_product::
            when($request->query_request, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
            ;})->
        get();
        $locale_products = $locale_products->where('locale', '=', 'us');

        if($locale_products->count() != 0){
            $serching_global_products = [];

            foreach ($locale_products as $locale_product) {
                if($locale_product->global_product_us->published = 1) {
                    array_push($serching_global_products, $locale_product->global_product_us);
                }
            }
            return ProductService::get_locale_product_use_locale($serching_global_products, 'us');
        }
        return [];
    }

    public function articleSearch(Request $request)
    {
        $locale_articles = Locale_article::
            when($request->query_request, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
            ;})->
        get();
        $locale_articles = $locale_articles->where('locale', '=', 'us');

        if($locale_articles->count() != 0){
            $serching_global_articles = [];

            foreach ($locale_articles as $locale_article) {
                if($locale_article->global_article_us->published = 1) {
                    array_push($serching_global_articles, $locale_article->global_article_us);
                }
            }
            return ArticlesService::get_locale_article_use_locale($serching_global_articles, 'us');
        }
        return [];
    }

    public function filmSearch(Request $request)
    {
        $locale_films = Locale_film::
            where('locale', '=', 'us')->
            when($request->query_request, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
            ;})->
        get();
        // dd($locale_films);
        $total_films = [];

        foreach ($locale_films as $l_films) {
            if($l_films -> global_film_us){
                array_push($total_films, ["us_film" => $l_films, "global_film" => $l_films -> global_film_us]);
            }
        }

        return $total_films;
    }

    function check_leng($text) {
        // $text = 'test';
        // $language = $this->detector->detect($text);
        // dd($language);
    }
}

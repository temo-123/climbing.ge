<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// for article searching
use App\Models\Article;
use App\Models\Locale_article;

// for product searching
use App\Models\Product;
use App\Models\Locale_product;
use App\Services\GetProductService;

// for film searching
use App\Models\Film;
use App\Models\Locale_film;

class SearchController extends Controller
{
    public function productSearch(Request $request)
    {

        $locale_products = Locale_product::
            where('locale', '=', 'us')->
            when($request->query_request, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
            ;})->
        get();

        $total_products = [];

        foreach ($locale_products as $l_products) {
            if($l_products -> global_product_us){
                // dd();
                array_push($total_products, [
                    "us_product" => $l_products, 
                    "global_product" => $l_products -> global_product_us,
                    "category"=>Product::find($l_products -> global_product_us->id)->product_category,
                    "max_price"=>GetProductService::get_product_price($l_products -> global_product_us->id, 'max'),
                    "min_price"=>GetProductService::get_product_price($l_products -> global_product_us->id, 'min'),
                    "product_images"=>GetProductService::get_product_images($l_products -> global_product_us->id),
                ]);
            }
        }

        return $total_products;
    }

    public function articleSearch(Request $request)
    {
        $locale_articles = Locale_article::
            where('locale', '=', 'us')->
            when($request->query_request, function ($query, $search) {
                $query->where('title', 'like', "%$search%")
                ->orWhere('short_description', 'like', "%$search%")
            ;})->
        get();

        $total_articles = [];

        foreach ($locale_articles as $l_articles) {
            if($l_articles -> global_article_us){
                array_push($total_articles, ["us_article" => $l_articles, "global_article" => $l_articles -> global_article_us]);
            }
        }

        return $total_articles;
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
}

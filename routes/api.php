<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace'=>'Api'], function() {
    Route::apiResource('/services', 'ServicesController');

    Route::patch('get_similar_product', 'ProductController@get_similar_product');
    // Route::patch('get_product_price_interval', 'ProductController@get_product_price_interval');
    Route::apiResource('/products', 'ProductController');
    Route::apiResource('/categories', 'CategoriesController');
    Route::apiResource('/cart', 'CartController');

    Route::apiResource('/gallery_image', 'GalleryController');
    Route::apiResource('/ice', 'IceController');
    Route::apiResource('/indoor', 'IndoorController');
    Route::apiResource('/mount', 'MountController');
    Route::apiResource('/mount_route', 'MountRouteController');
    Route::apiResource('/other', 'OtherController');
    Route::apiResource('/outdoor', 'OutdoorController');
    Route::apiResource('/event', 'EventController');
    Route::apiResource('/news', 'NewsController');
    Route::apiResource('/techtip', 'TechtipController');
    Route::apiResource('/swiper', 'SwiperController');
    Route::apiResource('/comment', 'CommentController');
    Route::apiResource('/siteData', 'SiteDataController');

    Route::apiResource('/route', 'RouteController');
    Route::apiResource('/sector', 'SectorController');
    Route::apiResource('/MTP', 'MTPController');
    Route::apiResource('/MTPPitch', 'MTPPitchController');

    Route::apiResource('/article', 'ArticleController');

    Route::apiResource('/message', 'MessageController');

    Route::apiResource('/productSearch', 'ProductsSearchController');
    Route::apiResource('/articleSearch', 'ArticlesSearchController');
});

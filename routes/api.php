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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::middleware('auth:sanctum')->get('/user', function(Request $request) {
//     return $request->user();
// });

Route::get('login/{provider}/callback','Auth\SocialController@Callback');
Route::get('login/{provider}', 'Auth\SocialController@redirect');

Route::middleware('auth:sanctum')->get('token', function () {
    return auth()->user()->createToken('authToken')->plainTextToken;
});

Route::group(['namespace'=>'Api'], function() {
    /*
    *   Article routes
    */
    Route::apiResource('/article', 'ArticleController');
    Route::get('/articles/get_editing_data/{id}', 'ArticleController@get_editing_data');
    Route::get('/articles/{category}/{lang}', 'ArticleController@get_locale_articles');
    Route::get('/article/{category}/{lang}/{url_title}', 'ArticleController@get_locale_article_on_page');
    Route::post('/similar_article/{lang}', 'ArticleController@get_similar_locale_article');
    Route::post('/articles/upload_spot_rock_images', 'ArticleController@upload_spot_rock_images');
    Route::post('/articles/upload_image', 'ArticleController@image_upload');
    Route::post('/get_article_global_data/{leng}/{article_id}', 'ArticleController@get_article_global_data');
    Route::get('/get_articles_for_forum/{category}/{lang}', 'ArticleController@get_articles_for_forum');
    Route::get('/last_news/{lang}', 'ArticleController@get_last_news');

    Route::post('/articles/add_to_interested_events/', 'ArticleController@add_to_interested_events');
    Route::get('/articles/get_interested_events/', 'ArticleController@get_interested_events');
    Route::delete('/articles/del_interested_event/{article_id}', 'ArticleController@del_interested_event');

    /*
    *   Outdoor regions
    */
    Route::get('/outdoor/get_filtred_outdoor_spots_for_admin/{filter_id}', 'OutdoorController@get_filtred_outdoor_spots_for_admin');
    Route::get('/outdoor/get_filtred_outdoor_spots_for_gest/{lang}/{filter_id}', 'OutdoorController@get_filtred_outdoor_spots_for_gest');

    Route::post('/articles/add_to_favorite_outdoor_area/', 'ArticleController@add_to_favorite_outdoor_area');
    Route::get('/outdoor/get_faworite_outdoor_region', 'OutdoorController@get_faworite_outdoor_region');
    Route::delete('/outdoor/del_faworite_outdoor_region/{article_id}', 'OutdoorController@del_faworite_outdoor_region');

    Route::post('/outdoor/add_spot', 'OutdoorController@add_spot');
    Route::get('/outdoor/get_editing_spot_data/{id}', 'OutdoorController@get_editing_spot_data');
    Route::post('/outdoor/edit_spot/{id}', 'OutdoorController@edit_spot');
    Route::delete('/outdoor/del_spot/{id}', 'OutdoorController@del_spot');

    /*
    *   Mountain (mount routes) regions
    */
    Route::get('/mount_route/get_filtred_mount_route_for_admin/{filter_id}', 'MountRouteController@get_filtred_mount_route_for_admin');
    Route::get('/mount_route/get_filtred_mount_route_for_user/{lang}/{filter_id}', 'MountRouteController@get_filtred_mount_route_for_user');

    /*
    *   Mount (mountain system) routes
    */
    Route::apiResource('/mount', 'MountController');
    Route::get('/mounts/{lang}', 'MountController@get_locale_mounts');
    Route::get('/mount/{lang}/{mount_id}', 'MountController@get_locale_mount');
    Route::get('/mount/on_page/{lang}/{mount_route_id}', 'MountController@get_locale_mount_on_route_page');
    
    /*
    *   Product and product categories routes
    */
    Route::apiResource('/product', 'ProductController');
    Route::get('/products/{land}', 'ProductController@get_local_products');
    Route::get('/product/{land}/{url_title}', 'ProductController@get_local_product_in_page');
    Route::get('similar_product/{land}/{product_id}', 'ProductController@get_similar_product');
    Route::get('product_price_interval', 'ProductController@get_product_price_interval');
    Route::get('/get_user_favorite_products', 'ProductController@get_user_favorite_products');
    Route::get('/get_quick_product/{lang}/{product_id}', 'ProductController@get_quick_product');

    Route::apiResource('/product_category', 'ProductCategoryController');
    
    /*
    *   Services routes
    */
    Route::apiResource('/service', 'ServicesController');
    Route::get('/services/{lang}', 'ServicesController@get_local_services');
    Route::get('/service/{land}/{url_title}', 'ServicesController@get_local_service_in_page');
    Route::get('/similar_services/{land}/{id}', 'ServicesController@get_similar_service');

    /*
    *   Cart and orders routes
    */
    Route::apiResource('/cart', 'CartController');
    Route::post('/cart/update_quantity/{item_id}', 'CartController@update_quantity');

    Route::post('/add_to_favorite/{product_id}', 'CartController@add_to_favorite');
    Route::post('/del_from_favorite/{product_id}', 'CartController@del_from_favorite');

    Route::apiResource('/order', 'OrderController');
    Route::get('/my_order', 'OrderController@get_my_orders');
    Route::get('/get_activ_order/{order_id}', 'OrderController@get_activ_order');
    Route::post('/edit_order_status/{order_id}', 'OrderController@edit_order_status');
    Route::get('/get_order_detals/{order_id}', 'OrderController@get_order_detals');
    Route::get('/get_order_products/{order_id}', 'OrderController@get_order_products');

    Route::post('/order_is_confirm/{order_id}', 'OrderController@order_is_confirm');
    Route::get('/is_order_confirm/{order_id}', 'OrderController@is_order_confirm');

    Route::apiResource('/sale_code', 'SaleCodeController');
    Route::post('/check_sale_code', 'OrderController@check_sale_code');

    /*
    *   Cart and orders routes
    */
    Route::get('/get_countries', 'ShipedCountryController@get_countries');
    Route::get('/get_activ_country/{country_id}', 'ShipedCountryController@get_activ_country');
    Route::post('/add_country', 'ShipedCountryController@add_country');
    Route::post('/edit_country/{country_id}', 'ShipedCountryController@edit_country');
    Route::delete('/del_country/{country_id}', 'ShipedCountryController@del_country');

    /*
    *   Guid gallery images routes
    */
    Route::apiResource('/gallery_image', 'GalleryController');
    Route::get('/swiper', 'GalleryController@get_swiper_images');
    Route::get('/get_image/{image_id}', 'GalleryController@get_image');
    Route::apiResource('/gallery_image_category', 'GalleryImagesCategoryController');

    /*
    *   Site data routes
    */
    Route::apiResource('/siteData', 'SiteDataController');
    Route::get('/site_data_counts', 'SiteDataController@site_data_counts');

    Route::apiResource('/general_info', 'GeneralInfoController');

    /*
    *   Guid Coments routes
    */
    Route::apiResource('/comment', 'CommentController');
    Route::get('/get_my_comment', 'CommentController@get_my_comments');
    Route::delete('/del_my_comment/{comment_id}', 'CommentController@del_my_comment');
    Route::post('/del_user_comment/{comment_id}', 'CommentController@del_user_comment');
    Route::get('/get_quick_comment/{comment_id}', 'CommentController@get_quick_comment');

    Route::get('/get_comments_complaints', 'CommentController@get_comments_complaints');
    Route::get('/get_user_queries/{user_id}', 'CommentController@get_user_queries');
    Route::post('/query_response', 'CommentController@query_response');

    Route::post('/add_comment_complaint', 'CommentController@add_comment_complaint');
    Route::post('/make_decision', 'CommentController@make_decision');

    /*
    *   Films routes
    */
    Route::apiResource('/films', 'FilmsController');
    Route::get('/film/get_films/{locale}', 'FilmsController@get_films');
    Route::get('/film/get_same_films/{category_id}/{film_id}/{locale}', 'FilmsController@get_same_films');
    Route::get('/film/get_film/{locale}/{url_title}', 'FilmsController@get_film');
    Route::get('/film/get_films_categories/{locale}', 'FilmsController@get_films_categories');
    Route::get('/film/films_search/{locale}', 'FilmsController@films_search');
    Route::get('/film/top_films/{top_film_type}/{locale}', 'FilmsController@get_films_top');

    Route::post('/film/add_to_faworite', 'FilmsController@add_to_faworite');
    Route::get('/film/get_faworite_film_list', 'FilmsController@get_faworite_film_list');
    Route::delete('/film/del_from_faworite/{film_id}', 'FilmsController@del_from_faworite');

    Route::apiResource('film_tags', 'FilmTagsController');
    // Route::apiResource('film_categories', 'FilmCategoryController');

    /*
    *   Guid sport sectors routes
    */
    Route::apiResource('/sector', 'SectorController');
    Route::get('/get_sectors_for_forum/{article_id}', 'SectorController@get_sectors_for_forum');
    Route::get('/sectors_and_routes_quantity', 'SectorController@get_sectors_and_routes_quantity');
    Route::get('/get_spot_rocks_images/{article_id}', 'SectorController@get_spot_rocks_images');

    Route::apiResource('/route', 'RouteController');
    Route::get('/get_routes_for_forum/{sector_id}', 'RouteController@get_routes_for_forum');
    Route::get('/get_routes_quantity/{article_id}', 'RouteController@get_routes_quantity');
    Route::post('/add_route', 'RouteController@add_route');

    Route::apiResource('/MTP', 'MTPController');
    Route::get('/get_mtps_for_forum/{sector_id}', 'MTPController@get_mtps_for_forum');
    
    Route::apiResource('/MTPPitch', 'MTPPitchController');

    /*
    *   Users routes
    */
    Route::apiResource('/users', 'UsersController');
    Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    
    Route::get('/followers_list', 'UsersController@get_followers_list');
    Route::get('/following_users_list', 'UsersController@get_following_users_list');
    Route::post('/follow/{service_id}', 'UsersController@follow');

    Route::get('/options/get_user_data', 'UsersController@get_user_data');
    Route::post('/options/user_info_update/{user_id}', 'UsersController@user_info_update');

    Route::get('/options/get_user_notification_data', 'UsersController@get_user_notification_data');
    Route::post('/options/update_user_notification_data/{notification_id}', 'UsersController@update_user_notification_data');
    
    /*
    *   Users routes
    */
    Route::apiResource('/user_site', 'UserSiteController');

    /*
    *   User adresses
    */
    Route::get('/get_user_adreses', 'CartController@get_user_adreses');
    Route::post('/add_user_adreses', 'CartController@add_user_adreses');
    Route::post('/edit_adres/{adres_id}', 'CartController@edit_adres');
    Route::post('/get_editing_adres/{adres_id}', 'CartController@get_editing_adres');
    Route::get('/get_activ_adres/{adres_id}', 'CartController@get_activ_adres');
    Route::delete('/del_user_adreses/{adres_id}', 'CartController@del_user_adreses');

    /*
    *   User roles and pormisions routes
    */
    Route::apiResource('/role', 'RolesController');
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    /*
    *   Climbing regions routes
    */
    Route::apiResource('/region', 'RegionController');
    Route::get('/regions/{lang}', 'RegionController@locale_regions');
    Route::get('/region/{lang}/{region_id}', 'RegionController@locale_region');

    /*
    *   Forum posts routes
    */
    Route::apiResource('/post', 'PostController');
    Route::post('/posts/add_post', 'PostController@add_post');
    Route::get('/posts/get_likes/{post_id}', 'PostController@get_likes');
    Route::get('/posts/get_route_posts/{route_id}', 'PostController@get_route_posts');
    Route::get('/posts/get_mtp_posts/{mtp_id}', 'PostController@get_mtp_posts');
    Route::post('/posts/get_posts_for_outdoor_region/{article_id}', 'PostController@get_posts_for_outdoor_region');
    
    Route::apiResource('/posts_topic', 'PostsTopicController');
    Route::get('/posts_topic/list/{lang}', 'PostsTopicController@get_local_topics');

    Route::get('/post_comment/get_post_comment/{post_id}', 'PostCommentsController@get_post_comment');
    Route::post('/post_comment/add_post_comment/{post_id}', 'PostCommentsController@add_post_comment');
    Route::post('/post_comment/edit_post_comment/{comment_id}', 'PostCommentsController@edit_post_comment');
    Route::delete('/post_comment/edit_post_comment/{comment_id}', 'PostCommentsController@edit_post_comment');

    /*
    *   Guidbook and Ploducts Search routes
    */
    Route::post('/productSearch/{query_request}', 'SearchController@productSearch');
    Route::post('/articleSearch/{query_request}', 'SearchController@articleSearch');
    Route::post('/filmSearch/{query_request}', 'SearchController@filmSearch');

    /*
    *   Mails routes
    */
    Route::group(['namespace'=>'Meil'], function() {
        Route::apiResource('/message', 'MessageController');
        Route::post('/FollowingNotification', 'FollowingNotificationController@send_notification');
    });

    /*
    *   Login verify routes
    */
    Route::group(['middleware'=>'auth:sanctum'], function() {
        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
        Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
    });

    // Route::get('/mount_route/{lang}', 'MountRouteController@locale_data');
    // Route::get('/other/{lang}', 'OtherController@locale_data');
    // Route::get('/event/{lang}', 'EventController@locale_data');
    // Route::get('/news/{lang}', 'NewsController@locale_data');
    // Route::get('/techtip/{lang}', 'TechtipController@locale_data');
    // Route::get('/ice/{lang}', 'IceController@locale_data');
    // Route::get('/indoor/{lang}', 'IndoorController@locale_data');
    // Route::get('/outdoor/{lang}', 'OutdoorController@locale_data');
    // Route::get('/outdoor/{lang}/{url_title}', 'OutdoorController@page_locale_data');

    // Route::apiResource('/ice', 'IceController');
    // Route::apiResource('/indoor', 'IndoorController');
    // Route::apiResource('/mount', 'MountController');
    // Route::apiResource('/mount_route', 'MountRouteController');
    // Route::apiResource('/other', 'OtherController');
    // Route::apiResource('/outdoor', 'OutdoorController');
    // Route::apiResource('/event', 'EventController');
    // Route::apiResource('/news', 'NewsController');
    // Route::apiResource('/techtip', 'TechtipController');
});

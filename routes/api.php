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
    Route::post('/article/add_article/{category}', 'ArticleController@add_article');
    Route::get('/articles/get_editing_data/{id}', 'ArticleController@get_editing_data');
    Route::get('/articles/{category}/{lang}', 'ArticleController@get_locale_articles');
    Route::get('/article/{category}/{lang}/{url_title}', 'ArticleController@get_locale_article_on_page');
    Route::post('/similar_article/{lang}', 'ArticleController@get_similar_locale_article');
    Route::post('/articles/upload_spot_rock_images', 'ArticleController@upload_spot_rock_images');
    Route::post('/articles/upload_image', 'ArticleController@image_upload');
    Route::post('/get_article_global_data/{leng}/{article_id}', 'ArticleController@get_article_global_data');
    Route::get('/get_articles_for_forum/{category}/{lang}', 'ArticleController@get_articles_for_forum');
    Route::get('/last_news/{lang}', 'ArticleController@get_last_news');

    /*
    *   Outdoor regions
    */
    Route::controller(OutdoorController::class)->prefix('outdoor')->group( function() {
        Route::get('/get_filtred_outdoor_spots_for_admin/{filter_id}', 'get_filtred_outdoor_spots_for_admin');
        Route::get('/get_filtred_outdoor_spots_for_gest/{lang}/{filter_id}', 'get_filtred_outdoor_spots_for_gest');

        Route::post('/add_spot', 'add_spot');
        Route::get('/get_editing_spot_data/{id}', 'get_editing_spot_data');
        Route::post('/edit_spot/{id}', 'edit_spot');
        Route::delete('/del_spot/{id}', 'del_spot');
    });

    /*
    *   Events
    */
    Route::controller(EventController::class)->prefix('event')->group( function() {
        Route::get('/get_events_for_site', 'get_events_for_site');
        Route::get('/get_all_events', 'get_all_events');
        Route::post('/add_event', 'add_event');
        Route::post('/edit_event/{event_id}', 'edit_event');
        Route::get('/get_editing_event/{event_id}', 'get_editing_event');
        Route::delete('/del_event/{event_id}', 'del_event');
    });

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
    Route::post('/edit_product_data/{product_id}', 'ProductController@edit_product_data');
    Route::get('/products/{land}', 'ProductController@get_local_products');
    Route::get('/page_product/{land}/{url_title}', 'ProductController@get_local_product_in_page');
    Route::get('similar_product/{land}/{product_id}', 'ProductController@get_similar_product');
    Route::get('product_price_interval', 'ProductController@get_product_price_interval');
    Route::get('/get_user_favorite_products', 'ProductController@get_user_favorite_products');
    Route::get('/get_quick_product/{lang}/{product_id}', 'ProductController@get_quick_product');

    Route::apiResource('/product_category', 'ProductCategoryController');

    Route::controller(ProductOptionController::class)->prefix('product_option')->group( function() {
        Route::get('/get_activ_product_options/{product_id}', 'get_activ_product_options');
        Route::post('/add_option', 'add_option');
        Route::post('/edit_option/{option_id}', 'edit_option');
        Route::get('/get_editing_option/{option_id}', 'get_editing_option');
        Route::delete('/del_option/{option_id}', 'del_option');
        Route::delete('/del_option_image/{image_id}', 'del_option_image');
    });

    /*
    *   Local bisnes routes
    */
    Route::controller(LocalBisnesController::class)->prefix('bisnes')->group( function() {
        Route::get('/get_local_bisneses', 'get_local_bisneses');
        Route::post('/add_local_bisnes', 'add_local_bisnes');
        Route::get('/get_editing_local_bisnes_info/{bisnes_id}', 'get_editing_local_bisnes_info');
        Route::get('/get_bisnes_images/{bisnes_id}', 'get_bisnes_images');
        Route::post('/edit_local_bisnes/{bisnes_id}', 'edit_local_bisnes');
        Route::delete('/del_local_bisnes/{bisnes_id}', 'del_local_bisnes');
        Route::delete('/del_local_bisnes_image/{image_id}', 'del_local_bisnes_image');

        Route::get('/get_local_bisneses_images/{bisnes_id}', 'get_local_bisneses_images');
        // Route::delete('/del_local_bisneses_images/{bisnes_id}', 'del_local_bisneses_images');
    });
    
    /*
    *   Services routes
    */
    Route::controller(ServicesController::class)->prefix('service')->group( function() {
        Route::apiResource('/', 'ServicesController');
        Route::post('/add_service', 'add_service');
        Route::post('/edit_service/{service_id}', 'edit_service');
        Route::get('/get_editing_service/{service_id}', 'get_editing_service');
        Route::get('/get_service_images/{service_id}', 'get_service_images');
        Route::get('/get_service/{service_id}', 'get_service');
        Route::delete('/del_service/{service_id}', 'del_service');
        Route::delete('/del_service_image/{image_id}', 'del_service_image');
        
        Route::get('/{land}/{url_title}', 'get_local_service_in_page');
    });
    Route::get('/services/{lang}', 'ServicesController@get_local_services');
    Route::get('/similar_services/{land}/{id}', 'ServicesController@get_similar_service');

    /*
    *   Cart and orders routes
    */
    Route::apiResource('/cart', 'CartController');
    Route::post('/cart/update_quantity/{item_id}', 'CartController@update_quantity');

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
    *   Faforites control
    */
    Route::post('/add_to_favorite/{product_id}', 'CartController@add_to_favorite');
    Route::post('/del_from_favorite/{product_id}', 'CartController@del_from_favorite');

    Route::post('/film/add_to_faworite', 'FilmsController@add_to_faworite');
    Route::get('/film/get_faworite_film_list', 'FilmsController@get_faworite_film_list');
    Route::delete('/film/del_from_faworite/{film_id}', 'FilmsController@del_from_faworite');

    Route::post('/articles/add_to_favorite_outdoor_area/', 'FaworitesController@add_to_favorite_outdoor_area');
    Route::get('/outdoor/get_faworite_outdoor_region', 'FaworitesController@get_faworite_outdoor_region');
    Route::delete('/outdoor/del_faworite_outdoor_region/{article_id}', 'FaworitesController@del_faworite_outdoor_region');

    Route::post('/articles/add_to_interested_events/', 'FaworitesController@add_to_interested_events');
    Route::get('/articles/get_interested_events/', 'FaworitesController@get_interested_events');
    Route::delete('/articles/del_interested_event/{article_id}', 'FaworitesController@del_interested_event');

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
    Route::post('/gallery_image_add', 'GalleryController@gallery_image_add');
    Route::post('/gallery_image_edit/{image_id}', 'GalleryController@gallery_image_edit');
    Route::get('/get_editing_image/{image_id}', 'GalleryController@get_editing_image');

    Route::apiResource('/gallery_image_category', 'GalleryImagesCategoryController');

    /*
    *   Site data routes
    */
    Route::controller(SiteDataController::class)->prefix('siteData')->group( function() {
        Route::apiResource('/', 'SiteDataController');
        Route::apiResource('/site_social_links', 'SocialLinkController');

        Route::get('/get_site_locale_data/{locale}', 'get_site_locale_data');

        Route::get('/get_site_global_data', 'get_site_global_data');
        Route::get('/get_site_ka_data', 'get_site_ka_data');
        Route::get('/get_site_ru_data', 'get_site_ru_data');
        Route::get('/get_site_us_data', 'get_site_us_data');

        Route::post('/edit_site_global_data', 'edit_site_global_data');
        Route::post('/edit_site_ka_data', 'edit_site_ka_data');
        Route::post('/edit_site_ru_data', 'edit_site_ru_data');
        Route::post('/edit_site_us_data', 'edit_site_us_data');
    });
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


    Route::apiResource('film_tags', 'FilmTagsController');
    // Route::apiResource('film_categories', 'FilmCategoryController');

    /*
    *   Guid sport sectors routes
    */
    Route::controller(SectorController::class)->prefix('sector')->group( function() {
        Route::apiResource('/', 'SectorController');
        Route::post('/add_sector', 'add_sector');
        Route::post('/edit_sector/{sector_id}', 'edit_sector');
        Route::delete('/del_sector_sector/{sector_id}', 'del_sector_sector');

        Route::get('/get_sector_images/{sector_id}', 'get_sector_images');
        Route::delete('/del_sector_image_from_db/{image_id}', 'del_sector_image_from_db');

        Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');
        Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');

        Route::post('/routes_sequence', 'routes_sequence');
    });

    Route::get('/get_sectors_for_forum/{article_id}', 'SectorController@get_sectors_for_forum');
    Route::get('/sectors_and_routes_quantity', 'SectorController@get_sectors_and_routes_quantity');
    Route::get('/get_spot_rocks_images/{article_id}', 'SectorController@get_spot_rocks_images');

    Route::controller(RouteController::class)->prefix('route')->group( function() {
        Route::apiResource('/', 'RouteController');
        Route::post('/add_route', 'add_route');
        Route::get('/get_route_editing_data/{route_id}', 'get_route_editing_data');
        Route::post('/edit_route/{route_id}', 'edit_route');
    });
    Route::get('/get_routes_for_forum/{sector_id}', 'RouteController@get_routes_for_forum');
    Route::get('/get_routes_quantity/{article_id}', 'RouteController@get_routes_quantity');

    Route::controller(MTPController::class)->prefix('mtp')->group( function() {
        Route::get('/', 'index');
        Route::post('/mtp_add', 'mtp_add');
        Route::post('/mtp_edit/{mtp_id}', 'mtp_edit');
        Route::get('/get_editing_mtp/{mtp_id}', 'get_editing_mtp');
        Route::delete('/del_mtp/{mtp_id}', 'del_mtp');

        Route::get('/get_mtps_for_forum/{sector_id}', 'get_mtps_for_forum');

        Route::controller(MTPPitchController::class)->prefix('mtp_pitch')->group( function() {
            Route::get('/', 'index');
            Route::post('/mtp_pitch_add', 'mtp_pitch_add');
            Route::post('/mtp_pitch_edit/{pitch_id}', 'mtp_pitch_edit');
            Route::get('/get_editin_pitch/{pitch_id}', 'get_editin_pitch');
            Route::delete('/del_pitch/{pitch_id}', 'del_pitch');

            Route::get('/get_mtp_pitchs_for_model/{mtp_id}', 'get_mtp_pitchs_for_model');
            Route::post('/pitchs_sequence', 'pitchs_sequence');
        });
    });
    

    Route::apiResource('/sector_local_images', 'SectorLocalImagesController');
    Route::post('/sector_local_images/update_image/{image_id}', 'SectorLocalImagesController@update_image');
    Route::get('/get_editing_sectors/{image_id}', 'SectorLocalImagesController@get_editing_sectors');
    Route::delete('/del_image_sector_from_db/{image_id}/{sector_id}', 'SectorLocalImagesController@del_image_sector_from_db');

    /*
    *   Users routes
    */
    Route::apiResource('/users', 'UsersController');
    Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    Route::post('user_image_update/{user_id}', 'UsersController@user_image_update');
    
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
    Route::apiResource('/region', 'link.');
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
});

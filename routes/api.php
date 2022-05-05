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
    Route::apiResource('/services', 'ServicesController');

    Route::get('get_similar_product', 'ProductController@get_similar_product');
    Route::get('get_product_price_interval', 'ProductController@get_product_price_interval');
    Route::apiResource('/products', 'ProductController');

    Route::apiResource('/categories', 'CategoriesController');
    Route::apiResource('/cart', 'CartController');
    Route::apiResource('/order', 'OrderController');
    Route::get('/my_order', 'OrderController@get_my_orders');

    Route::apiResource('/gallery_image', 'GalleryController');
    Route::apiResource('/gallery_image_category', 'GalleryImagesCategoryController');

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

    Route::apiResource('/general_info', 'GeneralInfoController');

    Route::apiResource('/siteData', 'SiteDataController');
    Route::get('/site_data_counts', 'SiteDataController@site_data_counts');

    Route::apiResource('/comment', 'CommentController');
    Route::get('/my_comment', 'CommentController@get_my_comments');

    Route::apiResource('/films', 'FilmsController');

    Route::apiResource('/route', 'RouteController');
    Route::apiResource('/sector', 'SectorController');
    Route::apiResource('/MTP', 'MTPController');
    Route::apiResource('/MTPPitch', 'MTPPitchController');

    Route::apiResource('/users', 'UsersController');
    Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
    Route::get('/following_users_list', 'UsersController@get_following_users_list');
    Route::post('/follow/{service_id}', 'UsersController@follow');

    Route::apiResource('/role', 'RolesController');
    Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

    Route::apiResource('/region', 'RegionController');
    Route::apiResource('/article', 'ArticleController');

    Route::apiResource('/post', 'PostController');
    Route::apiResource('/posts_topic', 'PostsTopicController');

    // Route::apiResource('/temporary_article', 'TemporaryArticleController');

    Route::apiResource('/productSearch', 'ProductsSearchController');
    Route::apiResource('/articleSearch', 'ArticlesSearchController');

    Route::group(['namespace'=>'Meil'], function() {
        Route::apiResource('/message', 'MessageController');
        Route::post('/FollowingNotification', 'FollowingNotificationController@send_notification');
    });

    Route::group(['middleware'=>'auth:sanctum'], function() {
        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
        Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
    });
});

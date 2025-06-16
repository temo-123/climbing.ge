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


Route::middleware('auth:sanctum')->get('token', function () {
    return auth()->user()->createToken('authToken')->plainTextToken;
});

// Route::get('login/{provider}/callback','Auth\SocialController@Callback');
// Route::get('login/{provider}', 'Auth\SocialController@redirect');

Route::group(['namespace'=>'Auth'], function() {
    Route::controller(VerificationController::class)->prefix('email')->group( function() {
        Route::get('/verify/{user_id}/{hash}', 'verify');
        // Route::get('/verify/{id}/{hash}', 'verify')->name('api.verification.verify');
        // Route::get('/resend', 'resend')->name('api.verification.resend'); // resending work on defolt laravel function
    });

    Route::controller(SocialController::class)->prefix('login')->group( function() {
        Route::get('/{provider}/callback','Callback');
        Route::get('/{provider}', 'redirect');
        Route::post('/social/create_password/{email}', 'create_password');
    });

    Route::controller(ForgotPasswordController::class)->prefix('password')->group( function() {
        Route::post('/send_forget_mail', 'send_forget_mail');
    });

    Route::controller(ResetPasswordController::class)->prefix('password')->group( function() {
        Route::post('/reset_password', 'reset_password');
    });
});

Route::group(['namespace'=>'Api'], function() {
    Route::group(['namespace'=>'Guide'], function() {
        
        /*
        *   Article routes
        */
        Route::controller(ArticleController::class)->prefix('article')->group( function() {
            // Route::apiResource('/', 'ArticleController');
            Route::get('/get_category_articles/{category}', 'get_category_articles');
            Route::get('/get_articles_for_bisnes_suport', 'get_articles_for_bisnes_suport');
            Route::get('/get_article_for_bisnes_page/{lang}/{bisnes_url_title}', 'get_article_for_bisnes_page');
            Route::get('/{category}/{lang}/{url_title}', 'get_locale_article_on_page');

            Route::post('/add_article/{category}', 'add_article');
            Route::post('/edit_article/{article_id}', 'edit_article');

            Route::delete('/del_article/{article_id}', 'del_article');

            // Route::post('/edit_article/{article_id}', 'edit_article');
        });

        Route::post('/similar_article/{lang}', 'ArticleController@get_similar_locale_article');
        Route::post('/articles/upload_spot_rock_images', 'ArticleController@upload_spot_rock_images');
        Route::post('/get_article_global_data/{leng}/{article_id}', 'ArticleController@get_article_global_data');

        Route::get('/get_articles_for_forum/{category}/{lang}', 'ArticleController@get_articles_for_forum');
        Route::get('/articles/get_editing_data/{id}', 'ArticleController@get_editing_data');
        Route::get('/articles/{category}/{lang}', 'ArticleController@get_locale_articles');
        Route::post('/articles/upload_image', 'ArticleController@image_upload');
        Route::get('/last_news/{lang}', 'ArticleController@get_last_news');

        /*
        *   Outdoor regions
        */
        Route::controller(OutdoorController::class)->prefix('outdoor')->group( function() {
            Route::get('/get_filtred_outdoor_spots_for_admin/{filter_id}', 'get_filtred_outdoor_spots_for_admin');
            // Route::get('/get_filtred_outdoor_spots_for_gest/{lang}/{filter_id}', 'get_filtred_outdoor_spots_for_gest');
            Route::get('/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}', 'get_filtred_outdoor_spots');

            Route::post('/add_spot', 'add_spot');
            Route::get('/get_editing_spot_data/{id}', 'get_editing_spot_data');
            Route::post('/edit_spot/{id}', 'edit_spot');
            Route::delete('/del_spot/{id}', 'del_spot');

            Route::prefix('region')->group( function() {
                Route::get('/', 'get_all');
                // Route::get('/', 'index');
                Route::get('/{lang}/{region_id}', 'locale_region');
            });
            Route::get('/regions/{lang}', 'locale_regions');
            Route::get('/get_spots_by_regions/{lang}', 'get_spots_by_regions');
        });

        /*
        *   Ice sectors
        */
        Route::controller(IceSectorController::class)->prefix('ice_sectors')->group( function() {
            Route::get('/get_all_sectors', 'get_all_sectors');
            Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');
            Route::get('/get_article_sectors/{article_id}', 'get_article_sectors');
            Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');
            Route::post('add_sector/', 'add_sector');
            Route::post('routes_sequence/', 'routes_sequence');
            Route::post('edit_sector/{sector_id}', 'edit_sector');
            Route::delete('del_sector/{sector_id}', 'del_sector');
        });

        /*
        *   Ice sector images
        */
        Route::controller(IceSectorImagesController::class)->prefix('ice_sector_images')->group( function() {
            Route::get('/get_sector_images/{sector_id}', 'get_sector_images');
            Route::post('add_sector_images', 'add_sector_images');
            Route::delete('del_sector_images/{image_id}', 'del_sector_images');
        });

        /*
        *   Ice routes
        */
        Route::controller(IceRouteController::class)->prefix('ice_routes')->group( function() {
            Route::get('/get_all_routes', 'get_all_routes');
            Route::get('/get_sector_routes/{sector_id}', 'get_sector_routes');
            Route::get('/get_route_data_for_modal/{route_id}', 'get_route_data_for_modal');
            Route::get('/get_route_editing_data/{sector_id}', 'get_route_editing_data');
            Route::post('add_route/', 'add_route');
            Route::post('edit_route/{route_id}', 'edit_route');
            Route::delete('del_route/{route_id}', 'del_route');
        });

        /*
        *   Events
        */
        Route::controller(EventController::class)->prefix('event')->group( function() {
            Route::get('/get_event_on_index_page/{lang}', 'get_event_on_index_page');

            Route::get('/get_events_for_site', 'get_events_for_site');
            Route::get('/get_all_events', 'get_all_events');
            Route::post('/add_event', 'add_event');
            Route::post('/edit_event/{event_id}', 'edit_event');
            Route::get('/get_editing_event/{event_id}', 'get_editing_event');
            Route::delete('/del_event/{event_id}', 'del_event');

            Route::get('/get_all_competitions', 'get_all_competitions');
            Route::post('/add_competition', 'add_competition');
            Route::post('/edit_competition/{competition_id}', 'edit_competition');
            Route::get('/get_editing_competition/{competition_id}', 'get_editing_competition');
            Route::delete('/del_competition/{competition_id}', 'del_competition');

            Route::get('/get_event_on_site_list/{lang}/', 'get_event_on_site_list');
            Route::get('/get_event_on_site_page/{lang}/{url_title}', 'get_event_on_site_page');

            Route::post('add_to_interested_events/', 'FaworitesController@add_to_interested_events');
            Route::get('get_interested_events/', 'FaworitesController@get_interested_events');
            Route::delete('del_interested_event/{article_id}', 'FaworitesController@del_interested_event');
        });

        /*
        *   Competitions
        */
        Route::controller(CompetitionController::class)->prefix('competition')->group( function() {
            Route::get('/get_all_competitions', 'get_all_competitions');
            Route::post('/add_competition', 'add_competition');
            Route::post('/edit_competition/{competition_id}', 'edit_competition');
            Route::get('/get_editing_competition/{competition_id}', 'get_editing_competition');
            Route::delete('/del_competition/{competition_id}', 'del_competition');
        });

        /*
        *   Mount routes
        */
        Route::controller(MountRouteController::class)->prefix('mount_route')->group( function() {
            Route::get('/get_filtred_mount_route_for_admin/{filter_id}', 'get_filtred_mount_route_for_admin');
            // Route::get('/get_filtred_mount_route_for_user/{lang}/{filter_id}', 'get_filtred_mount_route_for_user');
            Route::get('/get_filtred_mount_routes/{lang}/{filter_id}/{published}', 'get_filtred_mount_routes');

            Route::get('/get_mount_routes_by_maunt/{lang}', 'get_mount_routes_by_maunt');

            Route::get('/get_mount_routes_images/{article_id}', 'get_mount_routes_images');
            Route::delete('/del_mount_route_image/{image_id}', 'del_mount_route_image');
        });

        /*
        *   Mount system
        */
        Route::controller(MountController::class)->prefix('mount')->group( function() {
            Route::apiResource('/mount', 'MountController');
            Route::get('/get_editing_mount_data/{mount_id}', 'get_editing_mount_data');
            Route::get('/{lang}/{mount_id}', 'get_locale_mount');
            Route::get('/{lang}/{mount_id}', 'get_locale_mount');
            Route::get('/on_page/{lang}/{mount_route_id}', 'get_locale_mount_on_route_page');

            Route::post('/edit_mount_massive/{mount_id}', 'edit_mount_massive');
            Route::post('/create_mount_massive', 'create_mount_massive');
        });
        Route::get('/mounts/{lang}', 'MountController@get_locale_mounts');

        /*
        *   Local bisnes routes
        */
        Route::controller(LocalBisnesController::class)->prefix('bisnes')->group( function() {
            Route::get('/get_local_bisneses', 'get_local_bisneses');
            Route::get('/get_local_bisnes_for_article/{article_url_title}/{locale}', 'get_local_bisnes_for_article');
            Route::get('/get_local_bisnes_in_page/{url_title}/{locale}', 'get_local_bisnes_in_page');
            Route::get('/get_editing_local_bisnes_info/{bisnes_id}', 'get_editing_local_bisnes_info');

            Route::get('/get_bisnes_images/{bisnes_id}', 'get_bisnes_images');
            Route::get('/get_local_bisneses_images/{bisnes_id}', 'get_local_bisneses_images');
            Route::get('/get_bisnes_article_relation/{bisnes_id}', 'get_bisnes_article_relation');

            Route::post('/edit_local_bisnes/{bisnes_id}', 'edit_local_bisnes');
            Route::post('/add_local_bisnes', 'add_local_bisnes');

            Route::delete('/del_local_bisnes/{bisnes_id}', 'del_local_bisnes');
            Route::delete('/del_bisnes_article_relation/{article_id}/{bisnes_id}', 'del_bisnes_article_relation');
            Route::delete('/del_local_bisnes_image/{image_id}', 'del_local_bisnes_image');

        });

        /*
        *   Guid gallery images routes
        */
        Route::controller(ArticleGalleryController::class)->prefix('gallery_image')->group( function() {
            Route::get('/get_index_gallery', 'get_index_gallery');
            Route::get('/get_editing_images/{article_id}', 'get_editing_images');
    
            Route::delete('/del_image/{image_id}', 'del_image');
        });
        Route::controller(HeadSliderController::class)->prefix('head_slider')->group( function() {
            Route::get('/get_slides/{slide_category}', 'get_slides');
            Route::get('/get_all_slides', 'get_all_slides');
            Route::get('/get_actyve_slide/{slide_id}', 'get_actyve_slide');
    
            Route::post('/add_slide', 'add_slide');
            Route::post('/edit_slide/{slide_id}', 'edit_slide');

            Route::delete('/del_slide/{slide_id}', 'del_slide');
        });

        /*
        *   Site data routes
        */
        Route::controller(SiteDataController::class)->prefix('siteData')->group( function() {
            Route::apiResource('/', 'SiteDataController');

            // Route::apiResource('/site_social_links', 'SocialLinkController');

            Route::get('/get_site_locale_data/{locale}', 'get_site_locale_data');
            // Route::get('/get_locale_site_data/{locale}', 'get_locale_site_data');

            Route::get('/get_site_global_data', 'get_site_global_data');
            Route::get('/get_site_ka_data', 'get_site_ka_data');
            Route::get('/get_site_ru_data', 'get_site_ru_data');
            Route::get('/get_site_us_data', 'get_site_us_data');

            Route::post('/edit_site_data', 'edit_site_data');

            Route::post('/edit_site_global_data', 'edit_site_global_data');
            Route::post('/edit_site_ka_data', 'edit_site_ka_data');
            Route::post('/edit_site_ru_data', 'edit_site_ru_data');
            Route::post('/edit_site_us_data', 'edit_site_us_data');

            Route::get('/fix_article_bugs', 'fix_article_bugs');
        });
        Route::get('/site_data_counts', 'SiteDataController@site_data_counts');

        Route::apiResource('/general_info', 'GeneralInfoController');

        /*
        *   Guid sport sectors routes
        */
        Route::controller(SectorController::class)->prefix('sector')->group( function() {
            Route::apiResource('/', 'SectorController');
            Route::get('/get_sector_and_routes/{article_id}', 'get_sector_and_routes');
            Route::post('/add_sector', 'add_sector');
            Route::post('/edit_sector/{sector_id}', 'edit_sector');
            Route::delete('/del_sector_sector/{sector_id}', 'del_sector_sector');

            Route::get('/get_sector_images/{sector_id}', 'get_sector_images');
            Route::delete('/del_sector_image_from_db/{image_id}', 'del_sector_image_from_db');

            Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');
            Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');

            Route::post('/routes_sequence', 'routes_sequence');

            Route::get('/get_spot_sectors_data_for_model/{article_id}', 'get_spot_sectors_data_for_model');
            Route::post('/save_sector_sequence', 'save_sector_sequence');
            
        });

        Route::get('/get_sectors_for_forum/{article_id}', 'SectorController@get_sectors_for_forum');
        Route::get('/sectors_and_routes_quantity', 'SectorController@get_sectors_and_routes_quantity');
        Route::get('/get_spot_rocks_images/{article_id}', 'SectorController@get_spot_rocks_images');

        Route::controller(SpotRockController::class)->prefix('spot_rock_images')->group( function() {
            Route::get('/get_spot_rock_images/{article_id}', 'get_spot_rock_images');
            Route::delete('/del_spot_rock_image/{image_id}', 'del_spot_rock_image');
        });

        Route::controller(RouteController::class)->prefix('route')->group( function() {
            // Route::apiResource('/', 'RouteController');
            Route::get('/get_all_routes', 'get_all_routes');
            Route::get('/get_route_editing_data/{route_id}', 'get_route_editing_data');
            Route::get('/get_route_for_modal/{route_id}', 'get_route_for_modal');
            Route::get('/routes_authers', 'routes_authers');

            Route::post('/add_route', 'add_route');
            Route::post('/edit_route/{route_id}', 'edit_route');

            Route::delete('/del_route/{route_id}', 'del_route');
        });

        Route::get('/get_routes_for_forum/{sector_id}', 'RouteController@get_routes_for_forum');
        Route::get('/get_routes_quantity/{article_id}', 'RouteController@get_routes_quantity');

        Route::controller(MTPController::class)->prefix('mtp')->group( function() {
            Route::get('/', 'index');
            Route::get('/get_editing_mtp/{mtp_id}', 'get_editing_mtp');
            Route::get('/get_mtp_for_modal/{mtp_id}', 'get_mtp_for_modal');
            Route::get('/get_mtps_for_forum/{sector_id}', 'get_mtps_for_forum');

            Route::post('/mtp_add', 'mtp_add');
            Route::post('/mtp_edit/{mtp_id}', 'mtp_edit');

            Route::delete('/del_mtp/{mtp_id}', 'del_mtp');

            Route::controller(MTPPitchController::class)->prefix('mtp_pitch')->group( function() {
                Route::get('/', 'index');
                Route::get('/get_editin_pitch/{pitch_id}', 'get_editin_pitch');
                Route::get('/get_mtp_pitchs_for_model/{mtp_id}', 'get_mtp_pitchs_for_model');

                Route::post('/mtp_pitch_add', 'mtp_pitch_add');
                Route::post('/mtp_pitch_edit/{pitch_id}', 'mtp_pitch_edit');
                Route::post('/pitchs_sequence', 'pitchs_sequence');

                Route::delete('/del_pitch/{pitch_id}', 'del_pitch');
            });
        });
        

        Route::apiResource('/sector_local_images', 'SectorLocalImagesController');
        Route::post('/sector_local_images/update_image/{image_id}', 'SectorLocalImagesController@update_image');
        Route::get('/get_editing_sectors/{image_id}', 'SectorLocalImagesController@get_editing_sectors');
        Route::delete('/del_image_sector_from_db/{image_id}/{sector_id}', 'SectorLocalImagesController@del_image_sector_from_db');
        Route::controller(SectorLocalImagesController::class)->prefix('sector_local_img')->group( function() {
            Route::get('/get_sector_local_img_for_modal/{image_id}', 'get_sector_local_img_for_modal');
        });


        Route::controller(FaworitesController::class)->prefix('outdoor')->group( function() {
            Route::post('/add_to_favorite_outdoor_area/{article_id}', 'add_to_favorite_outdoor_area');
            Route::get('/get_faworite_outdoor_region', 'get_faworite_outdoor_region');
            Route::delete('/del_faworite_outdoor_region/{article_id}', 'del_faworite_outdoor_region');
        });

        /*
        *   Guid Coments routes get_article_comments
        */
        Route::controller(CommentController::class)->prefix('guide_comment')->group( function() {
            Route::get('/get_all_comments', 'get_all_comments');
            Route::get('/get_user_comments', 'get_user_comments');
            Route::get('/get_article_comments/{article_id}', 'get_article_comments');

            Route::get('/get_comments_complaints', 'get_comments_complaints');
            Route::get('/get_actyve_comment/{comment_id}', 'get_actyve_comment');

            Route::post('/create_comment/{article_id}', 'create_comment');
            Route::post('/confirm_email/{email}', 'confirm_email');
            Route::post('/add_comment_complaint', 'add_comment_complaint');
            Route::post('/make_decision', 'make_decision');

            Route::post('/hide_comment/{comment_id}', 'hide_comment');

            Route::delete('/del_comment/{comment_id}', 'del_comment');
        });

        /*
        *   Sport climbing routes reiting
        */

        Route::controller(RoutesReitingController::class)->prefix('route_review')->group( function() {
            Route::get('/get_user_review', 'get_user_review');
            Route::get('/get_all_review', 'get_all_review');
            Route::get('/get_actyve_review/{review_id}', 'get_actyve_review');

            Route::get('/get_all_route_reviews/{route_id}', 'get_all_route_reviews');

            Route::post('/create_route_review/{route_id}', 'create_route_review');
            Route::post('/edit_route_review/{review_id}', 'edit_route_review');

            Route::delete('/del_route_review/{review_id}', 'del_route_review');
        });
    });

    Route::group(['namespace'=>'Shop'], function() {
        /*
        *   Product and product categories routes
        */
        Route::controller(ProductController::class)->prefix('product')->group( function() {
            Route::apiResource('/', 'ProductController');
            Route::get('/get_all_products', 'get_all_products');
            Route::get('/get_user_products', 'get_user_products');
            Route::get('/get_product_editing_data/{product_id}', 'get_product_editing_data');
            Route::get('/add_product', 'add_product');

            Route::post('/edit_product/{product_id}', 'edit_product');
            Route::post('/add_product', 'add_product'); 

            Route::post('/change_user_relation', 'change_user_relation');
            Route::delete('/del_product/{product_id}', 'del_product');
        });
        Route::controller(ProductController::class)->group( function() {
            // Route::apiResource('/product', 'ProductController');
            Route::get('/products/{lang}', 'get_local_products');
            Route::get('/sale_products/{lang}', 'get_local_saled_products');
            Route::get('/page_product/{lang}/{url_title}', 'get_local_product_in_page');
            Route::get('similar_product/{lang}/{product_id}', 'get_similar_product');
            Route::get('product_price_interval', 'get_product_price_interval');
            Route::get('/get_user_favorite_products', 'get_user_favorite_products');
            Route::get('/get_quick_product/{lang}/{product_id}', 'get_quick_product');
        });

        Route::apiResource('/product_category', 'ProductCategoryController');

        Route::controller(ProductSubcategoryController::class)->prefix('subcategory')->group( function() {
            Route::get('/get_all_subcategories', 'get_all_subcategories');
            Route::get('/get_subcategories_for_category/{category_id}', 'get_subcategories_for_category');
            Route::get('/get_subcategory/{id}', 'get_subcategory');
            Route::post('/create_subcategory/{category_id}', 'create_subcategory');
            Route::post('/edit_subcategory/{id}', 'edit_subcategory');
            Route::delete('/del_subcategory/{id}', 'del_subcategory');
        });

        Route::controller(ProductBrandController::class)->prefix('brand')->group( function() {
            Route::get('/get_all_brands', 'get_all_brands');
            Route::get('/get_brand/{id}', 'get_brand');
            Route::post('/create_brand', 'create_brand');
            Route::post('/edit_brand/{id}', 'edit_brand');
            Route::delete('/del_brand/{id}', 'del_brand');
        });

        Route::controller(ProductOptionController::class)->prefix('product_option')->group( function() {
            Route::get('/get_activ_product_options/{product_id}', 'get_activ_product_options');
            Route::post('/add_option', 'add_option');
            Route::post('/edit_option/{option_id}', 'edit_option');
            Route::get('/get_editing_option/{option_id}', 'get_editing_option');
            Route::delete('/del_option/{option_id}', 'del_option');
            Route::delete('/del_option_image/{image_id}', 'del_option_image');
        });

        /*
        *   Tours and tour categories routes
        */
        Route::controller(TourController::class)->prefix('tour')->group( function() {
            Route::get('/get_tours/{lang}', 'get_tours');
            Route::get('/get_all_tours', 'get_all_tours');
            Route::get('/get_user_tours', 'get_user_tours');
            Route::get('/get_similar_tours/{lang}/{tour_id}', 'get_similar_tours');
            Route::get('/get_tour/{lang}/{url_title}', 'get_tour');
    
            Route::get('/get_editing_tour/{tour_id}', 'get_editing_tour');
            Route::get('/get_tour_images/{tour_id}', 'get_tour_images');
            Route::post('/add_tour', 'add_tour');
            Route::post('/edit_tour/{tour_id}', 'edit_tour');
            Route::post('/change_user_relation', 'change_user_relation');

            Route::delete('/del_tour_image/{image_id}', 'del_tour_image');
            Route::delete('/del_tour/{tour_id}', 'del_tour');
    
            Route::controller(TourCategoryController::class)->prefix('category')->group( function() {
                Route::get('/get_all_categories', 'get_all_categories');
    
                Route::get('/get_editing_category/{category_id}', 'get_editing_category');
                Route::post('/add_category', 'add_category');
                Route::post('/edit_category/{category_id}', 'edit_category');
                Route::delete('/del_category/{category_id}', 'del_category');
            });

            Route::controller(TourReservationController::class)->prefix('reservation')->group( function() {
                Route::get('/get_reservations', 'get_reservations');
                Route::get('/get_user_reservations', 'get_user_reservations');
    
                Route::post('/create_reservation/{tour_id}', 'create_reservation');
                Route::post('/verifiation_reservation/{reservation_id}', 'verifiation_reservation');
                
                Route::delete('/del_reservation/{reservation_id}', 'del_reservation');
            });
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
            
            Route::get('/{lang}/{url_title}', 'get_local_service_in_page');
        });
        Route::get('/services/{lang}', 'ServicesController@get_local_services');
        Route::get('/similar_services/{lang}/{id}', 'ServicesController@get_similar_service');
    
        /*
        *   Cart and orders routes
        */
        Route::controller(CartController::class)->group( function() {
            Route::apiResource('/cart', 'CartController');
            Route::post('/cart/update_quantity/{item_id}', 'update_quantity');

            /*
            *   Favorites control
            */
            Route::post('/add_to_favorite/{product_id}', 'add_to_favorite');
            Route::post('/del_from_favorite/{product_id}', 'del_from_favorite');
        });
    
        Route::controller(OrderController::class)->prefix('order')->group( function() {
            Route::get('/get_all_orders', 'get_all_orders');
            Route::post('/create_order', 'create_order');

            Route::get('/order/get_order_status/{order_id}', 'get_order_status');
            Route::get('/get_user_purchases', 'get_user_purchases');
            Route::get('/get_user_orders', 'get_user_orders');
            Route::get('/get_user_purchules', 'get_user_purchules');
            Route::get('/get_activ_order/{order_id}', 'get_activ_order');
            Route::post('/edit_order_status/{order_id}', 'edit_order_status');
            Route::get('/get_order_detals/{order_id}', 'get_order_detals');
            Route::get('/get_order_products/{order_id}', 'get_order_products');
            Route::get('/is_order_confirm/{order_id}', 'is_order_confirm');
        
            Route::post('/order_is_confirm/{order_id}', 'order_is_confirm');
            Route::post('/check_sale_code', 'check_sale_code');
            Route::post('/castam_prodaction_message/{product_id}', 'castam_prodaction_message');
        });
        Route::apiResource('/sale_code', 'SaleCodeController');

        /*
        *   Cart and orders routes
        */
        Route::controller(ShipedRegionController::class)->prefix('shiped_region')->group( function() {
            Route::get('/get_all_shiped_regions', 'get_all_shiped_regions');
            Route::get('/get_activ_region/{region_id}', 'get_activ_region');
            Route::post('/add_region', 'add_region');
            Route::post('/edit_region/{region_id}', 'edit_region');
            Route::delete('/del_region/{region_id}', 'del_region');
        });

        /*
        *   Product Coments routes
        */
        Route::controller(ProductFeedbackController::class)->prefix('product_feedback')->group( function() {
            // Route::apiResource('/feedback', 'feedbackController');
            Route::get('/get_all_feedbacks', 'get_all_feedbacks');
            Route::get('/get_user_feedbacks', 'get_user_feedbacks');
            Route::get('/get_product_feedbacks/{product_id}', 'get_product_feedbacks');

            Route::get('/get_feedbacks_complaints', 'get_feedbacks_complaints');
            Route::get('/get_actyve_feedback/{feedback_id}', 'get_actyve_feedback');

            Route::post('/create_feedback/{product_id}', 'create_feedback');
            Route::post('/confirm_email/{email}', 'confirm_email');

            Route::post('/add_feedback_complaint', 'add_feedback_complaint');
            Route::post('/make_decision', 'make_decision');

            Route::post('/hide_feedback/{feedback_id}', 'hide_feedback');

            Route::delete('/del_feedback/{feedback_id}', 'del_feedback');
        });
    
    });

    Route::group(['namespace'=>'Films'], function() {
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

        Route::post('/film/add_to_faworite', 'FilmsController@add_to_faworite');
        Route::get('/film/get_faworite_film_list', 'FilmsController@get_faworite_film_list');
        Route::delete('/film/del_from_faworite/{film_id}', 'FilmsController@del_from_faworite');
    });

    Route::group(['namespace'=>'Blog'], function() {
        /*
        *   Blog posts routes
        */
        Route::apiResource('/post', 'PostController');
        // Route::post('/posts/add_post', 'PostController@add_post');
        // Route::get('/posts/get_likes/{post_id}', 'PostController@get_likes');
        // Route::get('/posts/get_route_posts/{route_id}', 'PostController@get_route_posts');
        // Route::get('/posts/get_mtp_posts/{mtp_id}', 'PostController@get_mtp_posts');
        // Route::post('/posts/get_posts_for_outdoor_region/{article_id}', 'PostController@get_posts_for_outdoor_region');
        
        // Route::apiResource('/posts_topic', 'PostsTopicController');
        // Route::get('/posts_topic/list/{lang}', 'PostsTopicController@get_local_topics');

        // Route::get('/post_comment/get_post_comment/{post_id}', 'PostCommentsController@get_post_comment');
        // Route::post('/post_comment/add_post_comment/{post_id}', 'PostCommentsController@add_post_comment');
        // Route::post('/post_comment/edit_post_comment/{comment_id}', 'PostCommentsController@edit_post_comment');
        // Route::delete('/post_comment/edit_post_comment/{comment_id}', 'PostCommentsController@edit_post_comment');
    });

    Route::group(['namespace'=>'User'], function() {
        /*
        *   Login verify routes
        */
        Route::group(['middleware'=>'auth:sanctum'], function() {
            // Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');
            Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');
            Route::get('auth_user', 'AuthenticationController@user')->name('auth_user');
        });

        Route::controller(SocialLinkController::class)->prefix('site_social_links')->group( function() {
            Route::get('/get_site_social_links', 'get_site_social_links');
            Route::post('/add_site_social_links', 'add_site_social_links');
            Route::delete('/del_site_social_links/{link_id}', 'del_site_social_links');
        });

        /*
        *   Users routes
        */
        Route::apiResource('/user_site', 'UserSiteController');

        Route::controller(UsersController::class)->prefix('user')->group( function() {
            Route::post('/update_password', 'update_password');
            Route::get('/get_auth_user_permissions', 'get_auth_user_permissions');
            Route::get('/get_auth_user_data', 'get_auth_user_data');
            Route::get('/get_user_data/{user_id}', 'get_user_data');
            Route::get('/get_all_users', 'get_all_users');

            Route::get('/get_worker_users', 'get_worker_users');

            Route::post('/create_user_by_admin', 'create_user_by_admin');

            Route::delete('/del_user/{user_id}', 'del_user');

            Route::get('/post_user/{user_id}', 'get_post_user');
            Route::post('/user_image_update/{user_id}', 'user_image_update');

            Route::controller(UserNotificationsController::class)->prefix('notifications')->group( function() {
                Route::post('/send_user_favorites_notification/{action}', 'send_user_favorites_notification');
            });
        });
        
        /*
        *   Users option routes
        */
        // Route::apiResource('/users', 'UsersController');
        // Route::get('/post_user/{user_id}', 'UsersController@get_post_user');
        // Route::post('user_image_update/{user_id}', 'UsersController@user_image_update');
        
        
        Route::controller(UserOptionController::class)->prefix('options')->group( function() {
            // Route::get('/get_user_data', 'get_user_data');
            Route::get('/get_selected_user_data/{user_id}', 'get_selected_user_data');
            Route::post('/user_info_update/{user_id}', 'user_info_update');

            Route::get('/get_user_notification_data', 'get_user_notification_data');
            Route::post('/update_user_notification_data', 'update_user_notification_data');
        });

        Route::controller(ServiceFollowing::class)->prefix('follow')->group( function() {
            Route::post('/{service_id}', 'follow');
            Route::delete('/del_follower/{id}', 'del_follower');
            Route::get('/following_users_list', 'get_following_users_list');
        });

        /*
        *   Warehouse
        */
        Route::controller(WarehouseController::class)->prefix('warehouse')->group( function() {
            Route::get('/get_warehouses', 'get_warehouses');
            Route::post('/add_warehouses', 'add_warehouses');
            Route::post('/edit_warehouse/{warehouse_id}', 'edit_warehouse');
            Route::post('/get_editing_warehouse/{warehouse_id}', 'get_editing_warehouse');
            Route::get('/get_activ_warehouse/{warehouse_id}', 'get_activ_warehouse');
            Route::delete('/del_warehouse/{warehouse_id}', 'del_warehouse');
        });

        /*
        *   User adresses
        */
        Route::controller(UserAdresesController::class)->group( function() {
            Route::get('/get_user_adreses', 'get_user_adreses');
            Route::post('/add_user_adreses', 'add_user_adreses');
            Route::post('/edit_adres/{adres_id}', 'edit_adres');
            Route::post('/get_editing_adres/{adres_id}', 'get_editing_adres');
            Route::get('/get_activ_adres/{adres_id}', 'get_activ_adres');
            Route::delete('/del_user_adreses/{adres_id}', 'del_user_adreses');
        });

        /*
        *   User roles and pormisions routes
        */
        // Route::apiResource('/role', 'RolesController');
        Route::controller(RolesController::class)->prefix('role')->group( function() {
            Route::apiResource('/', 'RolesController');

            Route::get('get_editing_role/{role_id}', 'get_editing_role');
            Route::get('get_editing_role_permissions/{role_id}', 'get_editing_role_permissions');
            Route::post('create_role', 'create_role');
            Route::post('edit_role/{role_id}', 'edit_role');
            Route::delete('del_role_permission/{role_id}/{permission_id}', 'del_role_permission');
            Route::delete('del_role/{role_id}', 'del_role');

            Route::get('get_user_permissions/{user_id}', 'get_user_permissions');
            Route::post('edit_permissions_and_role/{user_id}', 'edit_permissions_and_role');
            Route::delete('del_user_pemisino/{permission_id}/{user_id}', 'del_user_pemisino');
        });
        Route::get('/parmisions_list', 'RolesController@get_parmisions_list');

        /*
        *   Permissions
        */
        Route::controller(PermissionsController::class)->prefix('permission')->group(function() {
            Route::get('get_parmisions_for_role/{role_id}', 'get_parmisions_for_role');
        });

        Route::prefix('task')->group( function() {
            Route::controller(TaskController::class)->group( function() {
                Route::get('/get_all_tasks', 'get_all_tasks');
                Route::get('/get_user_tasks', 'get_user_tasks');
                Route::get('/get_task_data/{task_id}', 'get_task_data');

                Route::post('/create_task', 'create_task');
                Route::post('/update_task/{task_id}', 'update_task');
                Route::post('/update_task_status/{task_id}', 'update_task_status');

                Route::delete('/del_task/{task_id}', 'del_task');
            });

            Route::controller(TaskCategoryController::class)->prefix('task_category')->group( function() {
                Route::get('/get_all_task_categories', 'get_all_task_categories');
                Route::get('/get_task_category_data/{task_category_id}', 'get_task_category_data');

                Route::post('/create_task_category', 'create_task_category');
                Route::post('/update_task_category/{task_category_id}', 'update_task_category');

                Route::delete('/del_task_category/{task_category_id}', 'del_task_category');
            });
        });

        Route::controller(NonRegisteredCommenterController::class)->prefix('non_registered_commenter')->group(function() {
            Route::get('get_non_registered_commenter', 'get_non_registered_commenter');
            Route::delete('del_non_registered_commenter/{id}', 'del_non_registered_commenter');
        });
    });
    
    Route::group(['namespace'=>'Meil'], function() {
        /*
        *   Mails routes
        */
        Route::apiResource('/message', 'MessageController');
        Route::post('/FollowingNotification', 'FollowingNotificationController@send_notification');
    });

    /*
    *   Guidbook and Ploducts Search routes
    */
    Route::post('/productSearch/{query_request}', 'SearchController@productSearch');
    Route::post('/articleSearch/{query_request}', 'SearchController@articleSearch');
    Route::post('/filmSearch/{query_request}', 'SearchController@filmSearch');

    /*
    *   Login verify routes
    */
    Route::controller(CKEditorController::class)->prefix('ckeditor')->group( function() {
        // Route::get('', 'index');
        Route::post('/upload', 'upload');
    });
});

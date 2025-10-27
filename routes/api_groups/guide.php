<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Guide'], function() {

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

    // Route::get('/get_articles_for_forum/{category}/{lang}', 'ArticleController@get_articles_for_forum');
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

    // /*
    // *   Ice sectors
    // */
    // Route::controller(IceSectorController::class)->prefix('ice_sectors')->group( function() {
    //     Route::get('/get_all_sectors', 'get_all_sectors');
    //     Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');
    //     Route::get('/get_article_sectors/{article_id}', 'get_article_sectors');
    //     Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');
    //     Route::post('add_sector/', 'add_sector');
    //     Route::post('routes_sequence/', 'routes_sequence');
    //     Route::post('edit_sector/{sector_id}', 'edit_sector');
    //     Route::delete('del_sector/{sector_id}', 'del_sector');
    // });

    // /*
    // *   Ice sector images
    // */
    // Route::controller(IceSectorImagesController::class)->prefix('ice_sector_images')->group( function() {
    //     Route::get('/get_sector_images/{sector_id}', 'get_sector_images');
    //     Route::post('add_sector_images', 'add_sector_images');
    //     Route::delete('del_sector_images/{image_id}', 'del_sector_images');
    // });

    // /*
    // *   Ice routes
    // */
    // Route::controller(IceRouteController::class)->prefix('ice_routes')->group( function() {
    //     Route::get('/get_all_routes', 'get_all_routes');
    //     Route::get('/get_sector_routes/{sector_id}', 'get_sector_routes');
    //     Route::get('/get_route_data_for_modal/{route_id}', 'get_route_data_for_modal');
    //     Route::get('/get_route_editing_data/{sector_id}', 'get_route_editing_data');
    //     Route::post('add_route/', 'add_route');
    //     Route::post('edit_route/{route_id}', 'edit_route');
    //     Route::delete('del_route/{route_id}', 'del_route');
    // });

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
        Route::delete('/del_sector/{sector_id}', 'del_sector');

        Route::get('/get_sector_images/{sector_id}', 'get_sector_images');
        Route::delete('/del_sector_image_from_db/{image_id}', 'del_sector_image_from_db');

        Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');
        Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');

        Route::get('/get_sectors_by_article_category/{article_category}', 'get_sectors_by_article_category');

        Route::post('/routes_sequence', 'routes_sequence');

        Route::get('/get_spot_sectors_data_for_model/{article_id}', 'get_spot_sectors_data_for_model');
        Route::post('/save_sector_sequence', 'save_sector_sequence');

    });

    // Route::get('/get_sectors_for_forum/{article_id}', 'SectorController@get_sectors_for_forum');
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

        Route::get('/get_routes_by_category/{category}', 'get_routes_by_category');
        Route::get('/get_routes_by_category_array', 'get_routes_by_category_array');

        Route::get('/get_related_routes_jsons', 'get_related_routes_jsons');
        Route::get('/get_route_jsons_for_sector_image', 'get_route_jsons_for_sector_image');

        Route::post('/add_route', 'add_route');
        Route::post('/edit_route/{route_id}', 'edit_route');

        Route::delete('/del_route/{route_id}', 'del_route');
    });

    // Route::get('/get_routes_for_forum/{sector_id}', 'RouteController@get_routes_for_forum');
    Route::get('/get_routes_quantity/{article_id}', 'RouteController@get_routes_quantity');

    Route::controller(RouteJsonController::class)->prefix('route_json')->group( function() {
        Route::get('/get_editing_route_json/{route_id}', 'get_editing_route_json');

        Route::post('/add_route_json', 'add_route_json');
        Route::post('/edit_route_json/{route_id}', 'edit_route_json');

        Route::delete('/del_route_json/{route_id}', 'del_route_json');
    });

    Route::controller(MTPController::class)->prefix('mtp')->group( function() {
        Route::get('/', 'index');
        Route::get('/get_editing_mtp/{mtp_id}', 'get_editing_mtp');
        Route::get('/get_mtp_for_modal/{mtp_id}', 'get_mtp_for_modal');
        // Route::get('/get_mtps_for_forum/{sector_id}', 'get_mtps_for_forum');

        Route::post('/mtp_add', 'mtp_add');
        Route::post('/mtp_edit/{mtp_id}', 'mtp_edit');

        Route::delete('/del_mtp/{mtp_id}', 'del_mtp');

        Route::controller(MTPPitchController::class)->prefix('mtp_pitch')->group( function() {
            Route::get('/', 'index');
            Route::get('/get_editin_pitch/{pitch_id}', 'get_editin_pitch');
            Route::get('/get_mtp_pitchs/{mtp_id}', 'get_mtp_pitchs');
            // Route::get('/get_mtp_pitchs/{mtp_id}', 'get_mtp_pitchs');

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
        Route::post('/save_canvas_data/{sector_id}', 'save_canvas_data');
        Route::get('/get_layout/{layout_id}', 'get_layout');
        Route::get('/get_layouts/{sector_local_image_id}', 'get_layouts');
        Route::get('/get_layout_old/{sector_id}', 'get_layout_old');
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

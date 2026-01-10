<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Guide'], function() {

    /*
    *   Article routes
    */
    Route::controller(ArticleController::class)->prefix('get_article')->group( function() {
        // Route::apiResource('/', 'ArticleController');
        Route::get('/get_category_articles/{category}', 'get_category_articles');
        Route::get('/get_articles_for_bisnes_suport', 'get_articles_for_bisnes_suport');
        Route::get('/get_article_for_bisnes_page/{lang}/{bisnes_url_title}', 'get_article_for_bisnes_page');
        
        Route::get('/get_locale_articles/{category}/{lang}', 'get_locale_articles');
        Route::get('/{category}/{lang}/{url_title}', 'get_locale_article_on_page');

        Route::get('/last_news/{lang}', 'get_last_news');
        Route::post('/get_similar_locale_article/{lang}', 'get_similar_locale_article');

        Route::controller(ArticleGalleryController::class)->prefix('get_gallery_image')->group( function() {
            Route::get('/get_index_gallery', 'get_index_gallery');
        });

        Route::controller(CommentController::class)->prefix('get_guide_comment')->group( function() {
            Route::get('/get_all_comments', 'get_all_comments');
            Route::get('/get_user_comments', 'get_user_comments');
            Route::get('/get_article_comments/{article_id}', 'get_article_comments');

            Route::get('/get_comments_complaints', 'get_comments_complaints');
            Route::get('/get_actyve_comment/{comment_id}', 'get_actyve_comment');

            // Editing routes moved to adminAction.php
        });

        Route::controller(CommentController::class)->prefix('set_guide_comment_by_gest')->group( function() {
            Route::post('/create_comment/{article_id}', 'create_comment');
        });
    });

    /*
    *   Outdoor regions
    */
    Route::controller(OutdoorController::class)->prefix('get_outdoor')->group( function() {
        Route::get('/get_filtred_outdoor_spots_for_admin/{filter_id}', 'get_filtred_outdoor_spots_for_admin');
        Route::get('/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}', 'get_filtred_outdoor_spots');

        Route::get('/get_spots_by_regions/{lang}', 'get_spots_by_regions');
    });

    Route::controller(RegionController::class)->prefix('get_region')->group( function() {
        Route::get('/get_all_outdoor_regions', 'get_all_outdoor_regions');
        Route::get('/get_local_region/{lang}/{region_id}', 'get_local_region');
        Route::get('/get_local_regions/{lang}', 'get_local_regions');
    });

    /*
    *   Events
    */
    Route::controller(EventController::class)->prefix('get_event')->group( function() {
        Route::get('/get_event_on_index_page/{lang}', 'get_event_on_index_page');

        Route::get('/get_events_for_site', 'get_events_for_site');
        Route::get('/get_all_events', 'get_all_events');

        Route::get('/get_all_competitions', 'get_all_competitions');
        Route::post('/add_competition', 'add_competition');
        Route::post('/edit_competition/{competition_id}', 'edit_competition');
        Route::get('/get_editing_competition/{competition_id}', 'get_editing_competition');
        Route::delete('/del_competition/{competition_id}', 'del_competition');

        Route::get('/get_event_on_site_list/{lang}/', 'get_event_on_site_list');
        Route::get('/get_event_on_site_page/{lang}/{url_title}', 'get_event_on_site_page');

    });

    /*
    *   Competitions
    */
    Route::controller(CompetitionController::class)->prefix('get_competition')->group( function() {
        Route::get('/get_all_competitions', 'get_all_competitions');
        Route::get('/get_editing_competition/{competition_id}', 'get_editing_competition');
    });

    /*
    *   Mount routes
    */
    Route::controller(MountRouteController::class)->prefix('get_mount_route')->group( function() {
        Route::get('/get_filtred_mount_route_for_admin/{filter_id}', 'get_filtred_mount_route_for_admin');
        Route::get('/get_filtred_mount_routes/{lang}/{filter_id}/{published}', 'get_filtred_mount_routes');

        Route::get('/get_mount_routes_by_maunt/{lang}', 'get_mount_routes_by_maunt');

        Route::get('/get_mount_routes_images/{article_id}', 'get_mount_routes_images');
    });

    /*
    *   Mount system
    */
    Route::controller(MountController::class)->prefix('get_mount')->group( function() {
        Route::get('/get_all_mount', 'get_all_mount');

        Route::get('/get_editing_mount_data/{mount_id}', 'get_editing_mount_data');
        Route::get('/{lang}/{mount_id}', 'get_locale_mount');
        Route::get('/{lang}/{mount_id}', 'get_locale_mount');
        Route::get('/on_page/{lang}/{mount_route_id}', 'get_locale_mount_on_route_page');

        // Editing routes moved to adminAction.php
        Route::get('/get_locale_mounts/{lang}', 'get_locale_mounts');
    });

    Route::controller(LiveCameraController::class)->prefix('get_live_camera')->group( function() {
        Route::get('/get_live_cameras', 'get_live_cameras');
        Route::get('/get_activ_live_camera/{id}', 'get_activ_live_camera');
    });


    /*
    *   Local bisnes routes
    */
    Route::controller(LocalBisnesController::class)->prefix('get_bisnes')->group( function() {
        Route::get('/get_local_bisneses', 'get_local_bisneses');
        Route::get('/get_local_bisnes_for_article/{article_url_title}/{locale}', 'get_local_bisnes_for_article');
        Route::get('/get_local_bisnes_in_page/{url_title}/{locale}', 'get_local_bisnes_in_page');

        Route::get('/get_bisnes_images/{bisnes_id}', 'get_bisnes_images');
        Route::get('/get_local_bisneses_images/{bisnes_id}', 'get_local_bisneses_images');
        Route::get('/get_bisnes_article_relation/{bisnes_id}', 'get_bisnes_article_relation');
        Route::get('/get_article_categories', 'get_article_categories');

        // Editing routes moved to adminAction.php

    });

    Route::controller(HeadSliderController::class)->prefix('get_head_slider')->group( function() {
        Route::get('/get_slides/{slide_category}', 'get_slides');
        Route::get('/get_all_slides', 'get_all_slides');
        Route::get('/get_actyve_slide/{slide_id}', 'get_actyve_slide');

        // Editing routes moved to adminAction.php
    });


    Route::controller(GeneralInfoController::class)->prefix('get_general_info')->group( function() {
        Route::get('/get_all_general_info', 'get_all_general_infos');
        Route::get('/get_general_info/{id}', 'get_general_info');
    });
    // Route::apiResource('/general_info', 'GeneralInfoController');

    /*
    *   Guid sport sectors routes
    */
    Route::controller(SectorController::class)->prefix('get_sector')->group( function() {
        // Route::apiResource('/', 'SectorController');

        Route::get('/get_all_sectors', 'get_all_sectors');

        Route::get('/get_sector_and_routes/{article_id}', 'get_sector_and_routes');
        Route::get('/get_sector_images/{sector_id}', 'get_sector_images');

        Route::get('/get_sector_data_for_model/{sector_id}', 'get_sector_data_for_model');

        Route::get('/get_sectors_by_article_category/{article_category}', 'get_sectors_by_article_category');

        Route::get('/get_spot_sectors_data_for_model/{article_id}', 'get_spot_sectors_data_for_model');

        Route::get('/sectors_and_routes_quantity', 'get_sectors_and_routes_quantity');
        Route::post('/sectors_and_routes_quantity_by_categories', 'sectors_and_routes_quantity_by_categories');
        Route::get('/get_spot_rocks_images/{article_id}', 'get_spot_rocks_images');

        Route::controller(SpotRockController::class)->prefix('get_spot_rock_images')->group( function() {
            Route::get('/get_spot_rock_images/{article_id}', 'get_spot_rock_images');
            // Editing routes moved to adminAction.php
        });


        // Route::apiResource('/sector_local_images', 'SectorLocalImagesController');
        // Route::get('/get_editing_sectors/{image_id}', 'SectorLocalImagesController@get_editing_sectors');
        Route::controller(SectorLocalImagesController::class)->prefix('get_sector_local_images')->group( function() {
            Route::get('/get_all_sector_local_images', 'get_all_sector_local_images');
            Route::get('/get_sector_local_img_for_modal/{image_id}', 'get_sector_local_img_for_modal');
            // Editing routes moved to adminAction.php
            Route::get('/get_layout/{layout_id}', 'get_layout');
            Route::get('/get_layouts/{sector_local_image_id}', 'get_layouts');
            Route::get('/get_layout_old/{sector_id}', 'get_layout_old');

            // Route::get('/get_editing_sectors/{image_id}', 'get_editing_sectors');
        });
    });


    Route::controller(RouteController::class)->prefix('get_route')->group( function() {
        // Route::apiResource('/', 'RouteController');
        Route::get('/get_all_routes', 'get_all_routes');
        // Route::get('/get_route_editing_data/{route_id}', 'get_route_editing_data');
        Route::get('/get_route_for_modal/{route_id}', 'get_route_for_modal');
        Route::get('/routes_authers', 'routes_authers');
        Route::post('/routes_authers_by_categories', 'routes_authers_by_categories');

        Route::get('/get_routes_by_category/{category}', 'get_routes_by_category');
        Route::get('/get_routes_by_category_array', 'get_routes_by_category_array');

        Route::get('/get_related_routes_jsons', 'get_related_routes_jsons');
        Route::get('/get_route_jsons_for_sector_image', 'get_route_jsons_for_sector_image');
        Route::get('/get_routes_quantity/{article_id}', 'get_routes_quantity');

        Route::get('/get_most_popular_routes/{route_type}', 'get_most_popular_routes');

            // Editing routes moved to adminAction.php
        Route::controller(RouteJsonController::class)->prefix('get_route_json')->group( function() {
            Route::get('/get_editing_route_json/{route_id}', 'get_editing_route_json');

            // Editing routes moved to adminAction.php
        });

        /*
        *   Sport climbing routes reiting
        */

        Route::controller(RoutesReitingController::class)->prefix('get_route_review')->group( function() {
            Route::get('/get_user_review', 'get_user_review');
            Route::get('/get_all_review', 'get_all_review');
            Route::get('/get_actyve_review/{review_id}', 'get_actyve_review');

            Route::get('/get_all_route_reviews/{route_id}', 'get_all_route_reviews');

            // Editing routes moved to adminAction.php
        });
    });


    Route::controller(MTPController::class)->prefix('get_mtp')->group( function() {
        Route::get('/get_all_mtp', 'index');
        Route::get('/get_mtp_for_modal/{mtp_id}', 'get_mtp_for_modal');
        // Route::get('/get_mtps_for_forum/{sector_id}', 'get_mtps_for_forum');

        // Editing routes moved to adminAction.php

        Route::controller(MTPPitchController::class)->prefix('get_mtp_pitch')->group( function() {
            Route::get('/get_all_mtp_pitchs', 'index');
            Route::get('/get_mtp_pitchs/{mtp_id}', 'get_mtp_pitchs');
            // Route::get('/get_mtp_pitchs/{mtp_id}', 'get_mtp_pitchs');

            // Editing routes moved to adminAction.php
        });
    });


    Route::controller(FaworitesController::class)->prefix('get_faworite')->group( function() {
        Route::get('/get_faworite_outdoor_region', 'get_faworite_outdoor_region');
        // Route::post('add_to_interested_events/', 'add_to_interested_events');
        Route::get('get_interested_events/', 'get_interested_events');
        // Route::delete('del_interested_event/{article_id}', 'del_interested_event');
        
        // Route::post('/add_to_favorite_outdoor_area/{article_id}', 'add_to_favorite_outdoor_area');
        Route::get('/get_faworite_outdoor_region', 'get_faworite_outdoor_region');
        // Route::delete('/del_faworite_outdoor_region/{article_id}', 'del_faworite_outdoor_region');
        
    });


});

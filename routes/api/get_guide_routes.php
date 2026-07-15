<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\Guide\Donations\DonationPaymentController;

Route::group(['namespace'=>'Api\Guide'], function() {
    Route::controller(ArticleController::class)->prefix('get_article')->group( function() {
        Route::get('/get_category_articles/{category}', 'get_category_articles');
        Route::get('/get_articles_for_bisnes_suport', 'get_articles_for_bisnes_suport');
        Route::get('/get_article_for_bisnes_page/{lang}/{bisnes_url_title}', 'get_article_for_bisnes_page');
        
        Route::get('/get_locale_articles/{category}/{lang}', 'get_locale_articles');
        Route::get('/get_locale_article_on_page/{category}/{lang}/{url_title}', 'get_locale_article_on_page');

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
        });

    });

    Route::controller(CommentController::class)->prefix('set_guide_comment_by_gest')->group( function() {
        Route::post('/create_comment/{article_id}', 'create_comment');
    });

    Route::controller(CommentController::class)->prefix('set_guide_comment')->group( function() {
        Route::post('/confirm_email/{email}', 'confirm_email');
    });

    Route::controller(TeamMemberController::class)->prefix('get_team')->group( function() {
        Route::get('/get_member_status/{id}', 'get_member_status');
        Route::get('/get_team_members', 'get_team_members');
        Route::get('/get_team_member_data/{id}', 'get_team_member_data');
    });

    Route::controller(OutdoorController::class)->prefix('get_outdoor')->group( function() {
        Route::get('/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}', 'get_filtred_outdoor_spots');
        Route::get('/get_filtred_outdoor_spots_for_gest/{lang}/{filter_id}', 'get_filtred_outdoor_spots_for_gest');
        Route::get('/get_spots_by_regions/{lang}', 'get_spots_by_regions');
    });

    Route::controller(RegionController::class)->prefix('get_region')->group( function() {
        Route::get('/get_all_outdoor_regions', 'get_all_outdoor_regions');
        Route::get('/get_local_region/{lang}/{region_id}', 'get_local_region');
        Route::get('/get_local_regions/{lang}', 'get_local_regions');
    });

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

    Route::controller(CompetitionController::class)->prefix('get_competition')->group( function() {
        Route::get('/get_all_competitions', 'get_all_competitions');
        Route::get('/get_editing_competition/{competition_id}', 'get_editing_competition');
    });

    Route::controller(MountRouteController::class)->prefix('get_mount_route')->group( function() {
        Route::get('/get_filtred_mount_routes/{lang}/{filter_id}/{published}', 'get_filtred_mount_routes');

        Route::get('/get_mount_routes_by_maunt/{lang}', 'get_mount_routes_by_maunt');

        Route::get('/get_mount_routes_images/{article_id}', 'get_mount_routes_images');
    });

    Route::controller(MountController::class)->prefix('get_mount')->group( function() {
        Route::get('/get_all_mount', 'get_all_mount');
        Route::get('/get_locale_mounts/{lang}', 'get_locale_mounts');

        Route::get('/get_editing_mount_data/{mount_id}', 'get_editing_mount_data');
        Route::get('/{lang}/{mount_id}', 'get_locale_mount');
        Route::get('/on_page/{lang}/{mount_route_id}', 'get_locale_mount_on_route_page');
    });

    Route::controller(LiveCameraController::class)->prefix('get_live_camera')->group( function() {
        Route::get('/get_live_cameras', 'get_live_cameras');
        Route::get('/get_activ_live_camera/{id}', 'get_activ_live_camera');
    });

    Route::controller(LocalBisnesController::class)->prefix('get_bisnes')->group( function() {
        Route::get('/get_local_bisneses', 'get_local_bisneses');
        Route::get('/get_local_bisnes_for_article/{article_url_title}/{locale}', 'get_local_bisnes_for_article');
        Route::get('/get_local_bisnes_in_page/{url_title}/{locale}', 'get_local_bisnes_in_page');

        Route::get('/get_bisnes_images/{bisnes_id}', 'get_bisnes_images');
        Route::get('/get_local_bisneses_images/{bisnes_id}', 'get_local_bisneses_images');
        Route::get('/get_bisnes_article_relation/{bisnes_id}', 'get_bisnes_article_relation');
        Route::get('/get_article_categories', 'get_article_categories');

    });

    Route::controller(HeadSliderController::class)->prefix('get_head_slider')->group( function() {
        Route::get('/get_slides/{slide_category}', 'get_slides');
        Route::get('/get_all_slides', 'get_all_slides');
        Route::get('/get_actyve_slide/{slide_id}', 'get_actyve_slide');
    });



    Route::controller(GeneralInfoController::class)->prefix('get_general_info')->group( function() {
        Route::get('/get_all_general_infos', 'get_all_general_infos');
        Route::get('/get_general_info/{id}', 'get_general_info');
        Route::post('/track_action', 'track_action');
    });

    Route::controller(SectorController::class)->prefix('get_sector')->group( function() {
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
        });


        Route::controller(SectorLocalImagesController::class)->prefix('get_sector_local_images')->group( function() {
            Route::get('/get_all_sector_local_images', 'get_all_sector_local_images');
            Route::get('/get_sector_local_img_for_modal/{image_id}', 'get_sector_local_img_for_modal');

            Route::get('/get_layout/{layout_id}', 'get_layout');
            Route::get('/get_layouts/{sector_local_image_id}', 'get_layouts');
        });

        Route::controller(SectorImageExtraDrawingController::class)->prefix('get_sector_image_extra_drawing')->group( function() {
            Route::get('/get/{sector_image_id}', 'get');
        });

        Route::controller(SectorLocalImageExtraDrawingController::class)->prefix('get_sector_local_image_extra_drawing')->group( function() {
            Route::get('/get/{sector_local_image_id}', 'get');
        });
    });


    Route::controller(RouteController::class)->prefix('get_route')->group( function() {
        Route::get('/get_all_routes', 'get_all_routes');
        Route::get('/get_route_for_modal/{route_id}', 'get_route_for_modal');
        Route::get('/routes_authers', 'routes_authers');
        Route::post('/routes_authers_by_categories', 'routes_authers_by_categories');

        Route::get('/get_routes_by_category/{category}', 'get_routes_by_category');
        Route::get('/get_routes_by_category_array', 'get_routes_by_category_array');

        Route::get('/get_related_routes_jsons', 'get_related_routes_jsons');
        Route::get('/get_route_jsons_for_sector_image', 'get_route_jsons_for_sector_image');
        Route::get('/get_routes_quantity/{article_id}', 'get_routes_quantity');

        Route::get('/get_most_popular_routes/{route_type}', 'get_most_popular_routes');

        Route::controller(RouteJsonController::class)->prefix('get_route_json')->group( function() {
            Route::get('/get_editing_route_json/{route_id}', 'get_editing_route_json');
        });

        Route::controller(RoutesReitingController::class)->prefix('get_route_review')->group( function() {
            Route::get('/get_user_review', 'get_user_review');
            Route::get('/get_all_review', 'get_all_review');
            Route::get('/get_actyve_review/{review_id}', 'get_actyve_review');

            Route::get('/get_all_route_reviews/{route_id}', 'get_all_route_reviews');
        });
    });

    Route::controller(MTPController::class)->prefix('get_mtp')->group( function() {
        Route::get('/get_all_mtp', 'index');
        Route::get('/get_mtp_for_modal/{mtp_id}', 'get_mtp_for_modal');

        Route::controller(MTPPitchController::class)->prefix('get_mtp_pitch')->group( function() {
            Route::get('/get_all_mtp_pitchs', 'index');
            Route::get('/get_mtp_pitchs/{mtp_id}', 'get_mtp_pitchs');
        });
    });

    Route::controller(MtpReitingController::class)->prefix('get_mtp_review')->group(function () {
        Route::get('/get_all_mtp_reviews/{mtp_id}', 'get_all_mtp_reviews');
        Route::get('/get_user_mtp_reviews', 'get_user_mtp_reviews');
    });

    Route::controller(\App\Http\Controllers\Api\Guide\Donations\DonationPaymentController::class)->prefix('set_donation')->group( function() {
        Route::post('/process', 'processDonation');

        Route::post('/create', 'create');
        Route::post('/callback', 'callback')->name('donations.callback');
        Route::get('/status/{id}', 'status');
    });

    Route::controller(\App\Http\Controllers\Api\Guide\Donations\DonationPaymentController::class)->prefix('get_donation')->group( function() {
        Route::get('/tbc_info', 'get_tbc_info');
    });
});

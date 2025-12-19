<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Guide'], function() {

    /*
    *   Article routes
    */
    Route::controller(ArticleController::class)->prefix('set_article')->group( function() {
    //     // Route::apiResource('/', 'ArticleController');
        // Route::get('/get_editing_data/{id}', 'get_editing_data');
        
    //     Route::get('/get_category_articles/{category}', 'get_category_articles');
    //     Route::get('/get_articles_for_bisnes_suport', 'get_articles_for_bisnes_suport');
    //     Route::get('/get_article_for_bisnes_page/{lang}/{bisnes_url_title}', 'get_article_for_bisnes_page');
    //     Route::get('/{category}/{lang}/{url_title}', 'get_locale_article_on_page');

        Route::post('/add_article/{category}', 'add_article');
        Route::post('/edit_article/{article_id}', 'edit_article');
        Route::delete('/del_article/{article_id}', 'del_article');
    //     Route::post('/similar_article/{lang}', 'get_similar_locale_article');
    //     Route::post('/upload_spot_rock_images', 'upload_spot_rock_images');
    //     Route::post('/get_article_global_data/{leng}/{article_id}', 'get_article_global_data');
    //     Route::post('/upload_image', 'image_upload');

        Route::get('/get_editing_data/{id}', 'get_editing_data');
    //     // Route::get('/{category}/{lang}', 'get_locale_articles');
    //     // Route::post('/upload_image', 'image_upload');
    //     Route::get('/last_news/{lang}', 'get_last_news');
    });
    /*
    *   Outdoor regions
    */
    Route::controller(OutdoorController::class)->prefix('set_outdoor')->group( function() {
        Route::get('/get_filtred_outdoor_spots_for_admin/{filter_id}', 'get_filtred_outdoor_spots_for_admin');
        Route::get('/get_filtred_outdoor_spots_for_gest/{lang}/{filter_id}', 'get_filtred_outdoor_spots_for_gest');
        Route::get('/get_filtred_outdoor_spots/{lang}/{filter_id}/{published}', 'get_filtred_outdoor_spots');

        // Route::post('/add_spot', 'add_spot');
        // Route::get('/get_editing_spot_data/{id}', 'get_editing_spot_data');
        // Route::post('/edit_spot/{id}', 'edit_spot');
        // Route::delete('/del_spot/{id}', 'del_spot');

    });

    Route::controller(RegionController::class)->prefix('set_region')->group( function() {
        Route::post('/add_region', 'add_region');
        Route::get('/get_editing_region_data/{id}', 'get_editing_region_data');
        Route::post('/edit_region/{id}', 'edit_region');
        Route::delete('/del_region/{id}', 'del_region');
    });

    // Route::controller(OutdoorController::class)->group( function() {
    // });

    Route::controller(SectorController::class)->prefix('set_sector')->group( function() {
        Route::get('/get_sector_local_img_for_modal/{image_id}', 'get_sector_local_img_for_modal');
        Route::post('/save_canvas_data/{sector_id}', 'save_canvas_data');
        Route::get('/get_layout/{layout_id}', 'get_layout');
        Route::get('/get_layouts/{sector_local_image_id}', 'get_layouts');
        Route::get('/get_layout_old/{sector_id}', 'get_layout_old');
    });

    Route::controller(TeamMemberController::class)->prefix('set_team')->group( function() {
        Route::post('/edit_member_status/{id}', 'edit_member_status');
    });
    
    Route::controller(NonRegisteredCommenterController::class)->prefix('set_non_registered_commenter')->group(function() {
        Route::get('get_non_registered_commenter', 'get_non_registered_commenter');
        Route::delete('/del_non_registered_commenter/{id}', 'del_non_registered_commenter');
    });
    /*
    *   Guid Coments routes get_article_comments
    */
    Route::controller(CommentController::class)->prefix('set_guide_comment')->group( function() {
        Route::get('/get_all_comments', 'get_all_comments');
        Route::get('/get_user_comments', 'get_user_comments');
        Route::get('/get_article_comments/{article_id}', 'get_article_comments');

        Route::get('/get_comments_complaints', 'get_comments_complaints');
        Route::get('/get_actyve_comment/{comment_id}', 'get_actyve_comment');

        // Route::post('/create_comment/{article_id}', 'create_comment');
        Route::post('/confirm_email/{email}', 'confirm_email');
        Route::post('/add_comment_complaint', 'add_comment_complaint');
        Route::post('/make_decision', 'make_decision');

        Route::post('/hide_comment/{comment_id}', 'hide_comment');

        Route::delete('/del_comment/{comment_id}', 'del_comment');
    });



    // Route::controller(CommentController::class)->prefix('set_guide_comment')->group( function() {
    //     // Route::post('/create_comment/{article_id}', 'create_comment');
    //     Route::post('/confirm_email/{email}', 'confirm_email');
    //     Route::post('/add_comment_complaint', 'add_comment_complaint');
    //     Route::post('/make_decision', 'make_decision');
    //     Route::post('/hide_comment/{comment_id}', 'hide_comment');
    //     Route::delete('/del_comment/{comment_id}', 'del_comment');
    // });

    /*
    *   Sport climbing routes reiting
    */

    Route::controller(RoutesReitingController::class)->prefix('set_route_review')->group( function() {
        Route::get('/get_user_review', 'get_user_review');
        Route::get('/get_all_review', 'get_all_review');
        Route::get('/get_actyve_review/{review_id}', 'get_actyve_review');

        Route::get('/get_all_route_reviews/{route_id}', 'get_all_route_reviews');

        Route::post('/create_route_review/{route_id}', 'create_route_review');
        Route::post('/edit_route_review/{review_id}', 'edit_route_review');

        Route::delete('/del_route_review/{review_id}', 'del_route_review');
    });

    /*
    *   Editing routes
    */

    Route::controller(EventController::class)->prefix('set_event')->group( function() {
        Route::post('/add_event', 'add_event');
        Route::get('/get_editing_event/{event_id}', 'get_editing_event');
        Route::post('/edit_event/{event_id}', 'edit_event');
        Route::delete('/del_event/{event_id}', 'del_event');
    });

    // Route::controller(CompetitionController::class)->group( function() {
    //     Route::post('/competition/add_competition', 'add_competition');
    //     Route::post('/competition/edit_competition/{competition_id}', 'edit_competition');
    //     Route::delete('/competition/del_competition/{competition_id}', 'del_competition');
    // });

    Route::controller(MountController::class)->prefix('set_mount')->group( function() {
        Route::post('/edit_mount_massive/{mount_id}', 'edit_mount_massive');
        Route::post('/add_mount_massive', 'add_mount_massive');

        Route::get('/get_editing_mount_massive_data/{mount_id}', 'get_editing_mount_massive_data');
        Route::delete('/del_mount_massive/{mount_id}', 'del_mount_massive');
    });

    Route::controller(LocalBisnesController::class)->prefix('set_bisnes')->group( function() {
        Route::post('/edit_local_bisnes/{bisnes_id}', 'edit_local_bisnes');
        Route::get('/get_editing_local_bisnes_info/{bisnes_id}', 'get_editing_local_bisnes_info');
        Route::post('/add_local_bisnes', 'add_local_bisnes');
        Route::delete('/del_local_bisnes/{bisnes_id}', 'del_local_bisnes');
        Route::delete('/del_bisnes_article_relation/{article_id}/{bisnes_id}', 'del_bisnes_article_relation');
        Route::delete('/del_local_bisnes_image/{image_id}', 'del_local_bisnes_image');
    });

    // Route::controller(ArticleGalleryController::class)->group( function() {
    //     Route::post('/gallery_image', 'get_index_gallery');
    // });

    Route::controller(ArticleGalleryController::class)->prefix('set_gallery_image')->group( function() {
        // Route::get('/get_index_gallery', 'get_index_gallery');
        Route::get('/get_editing_images/{article_id}', 'get_editing_images');

        Route::delete('/del_image/{image_id}', 'del_image');
        // Editing routes moved to adminAction.php
    });

    Route::controller(HeadSliderController::class)->prefix('set_head_slider')->group( function() {
        Route::post('/add_slide', 'add_slide');
        Route::post('/edit_slide/{slide_id}', 'edit_slide');
        Route::delete('/del_slide/{slide_id}', 'del_slide');
    });

    Route::controller(SiteDataController::class)->prefix('set_siteData')->group( function() {
        Route::post('/edit_site_data', 'edit_site_data');
        Route::post('/edit_site_global_data', 'edit_site_global_data');
        Route::post('/edit_site_ka_data', 'edit_site_ka_data');
        Route::post('/edit_site_ru_data', 'edit_site_ru_data');
        Route::post('/edit_site_us_data', 'edit_site_us_data');

        Route::get('/fix_article_bugs', 'fix_article_bugs');
        Route::get('/site_data_counts', 'site_data_counts');
    });

    Route::controller(SectorController::class)->prefix('set_sector')->group( function() {
        Route::post('/add_sector', 'add_sector');
        Route::post('/edit_sector/{sector_id}', 'edit_sector');
        Route::delete('/del_sector/{sector_id}', 'del_sector');
        Route::delete('/del_sector_image_from_db/{image_id}', 'del_sector_image_from_db');
        Route::post('/routes_sequence', 'routes_sequence');
        Route::post('/save_sector_sequence', 'save_sector_sequence');
        Route::get('/get_sector_editing_data/{sector_id}', 'get_sector_editing_data');
    });

    Route::controller(SpotRockController::class)->prefix('set_spot_rock_images')->group( function() {
        Route::delete('/del_spot_rock_image/{image_id}', 'del_spot_rock_image');
    });

    Route::controller(RouteController::class)->prefix('set_route')->group( function() {
        Route::post('/add_route', 'add_route');
        Route::get('/get_route_editing_data/{route_id}', 'get_route_editing_data');
        Route::post('/edit_route/{route_id}', 'edit_route');
        Route::delete('/del_route/{route_id}', 'del_route');
    });

    /*
    *   Live camera routes
    */
    // Route::controller(LiveCameraController::class)->prefix('set_live_camera')->group( function() {
    //     Route::get('/get_live_cameras', 'get_live_cameras');
    //     Route::get('/get_editing_live_camera/{id}', 'get_editing_live_camera');
    //     Route::get('/get_activ_live_camera/{id}', 'get_activ_live_camera');
    // });

    /*
    *   Live camera routes
    */
    Route::controller(LiveCameraController::class)->prefix('set_live_camera')->group( function() {
        // Route::get('/get_live_cameras', 'get_live_cameras');
        Route::get('/get_editing_live_camera/{id}', 'get_editing_live_camera');
        // Route::get('/get_activ_live_camera/{id}', 'get_activ_live_camera');
        Route::post('/add_live_camera', 'add_live_camera');
        Route::post('/edit_live_camera/{id}', 'edit_live_camera');
        Route::delete('/del_live_camera/{id}', 'del_live_camera');
    });

    Route::controller(RouteJsonController::class)->prefix('set_route_json')->group( function() {
        Route::post('/add_route_json', 'add_route_json');
        Route::post('/edit_route_json/{route_id}', 'edit_route_json');
        Route::delete('/del_route_json/{route_id}', 'del_route_json');
    });

    Route::controller(MTPController::class)->prefix('set_mtp')->group( function() {
        Route::post('/mtp_add', 'mtp_add');
        Route::get('/get_editing_mtp/{mtp_id}', 'get_editing_mtp');
        Route::post('/mtp_edit/{mtp_id}', 'mtp_edit');
        Route::delete('/del_mtp/{mtp_id}', 'del_mtp');

        Route::controller(MTPPitchController::class)->prefix('set_mtp_pitch')->group( function() {
            Route::post('/mtp_pitch_add', 'mtp_pitch_add');
            Route::get('/get_editin_pitch/{pitch_id}', 'get_editin_pitch');
            Route::post('/mtp_pitch_edit/{pitch_id}', 'mtp_pitch_edit');
            Route::post('/pitchs_sequence', 'pitchs_sequence');
            Route::delete('/del_pitch/{pitch_id}', 'del_pitch');
        });
    });

    Route::controller(SectorLocalImagesController::class)->prefix('set_sector_local_images')->group( function() {
        Route::post('/add_sector_local_image', 'add_sector_local_image');
        Route::post('/update_image/{image_id}', 'update_image');
        Route::get('/get_editing_locale_image/{image_id}', 'get_editing_locale_image');
        Route::delete('/del_image_sector_from_db/{image_id}/{sector_id}', 'del_image_sector_from_db');
        Route::delete('/del_locale_image/{sector_id}', 'del_locale_image');
        Route::post('/save_canvas_data/{sector_id}', 'save_canvas_data');

        Route::get('/get_editing_sectors/{image_id}', 'get_editing_sectors');
    });


    Route::controller(RoutesReitingController::class)->prefix('set_route_review')->group( function() {
        Route::post('/create_route_review/{route_id}', 'create_route_review');
        Route::post('/edit_route_review/{review_id}', 'edit_route_review');
        Route::delete('/del_route_review/{review_id}', 'del_route_review');
    });
});

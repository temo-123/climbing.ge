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
        });
    });

    Route::controller(SectorController::class)->prefix('sector')->group( function() {
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

    /*
    *   Editing routes
    */
    Route::controller(ArticleController::class)->group( function() {
        Route::post('/article/add_article/{category}', 'add_article');
        Route::post('/article/edit_article/{article_id}', 'edit_article');
        Route::delete('/article/del_article/{article_id}', 'del_article');
        Route::post('/similar_article/{lang}', 'get_similar_locale_article');
        Route::post('/articles/upload_spot_rock_images', 'upload_spot_rock_images');
        Route::post('/get_article_global_data/{leng}/{article_id}', 'get_article_global_data');
        Route::post('/articles/upload_image', 'image_upload');
    });

    Route::controller(OutdoorController::class)->group( function() {
        Route::post('/outdoor/add_spot', 'add_spot');
        Route::post('/outdoor/edit_spot/{id}', 'edit_spot');
        Route::delete('/outdoor/del_spot/{id}', 'del_spot');
    });

    Route::controller(EventController::class)->group( function() {
        Route::post('/event/add_event', 'add_event');
        Route::post('/event/edit_event/{event_id}', 'edit_event');
        Route::delete('/event/del_event/{event_id}', 'del_event');
    });

    Route::controller(CompetitionController::class)->group( function() {
        Route::post('/competition/add_competition', 'add_competition');
        Route::post('/competition/edit_competition/{competition_id}', 'edit_competition');
        Route::delete('/competition/del_competition/{competition_id}', 'del_competition');
    });

    Route::controller(FaworitesController::class)->group( function() {
        Route::post('event/add_to_interested_events/', 'add_to_interested_events');
        Route::delete('event/del_interested_event/{article_id}', 'del_interested_event');
        Route::post('/outdoor/add_to_favorite_outdoor_area/{article_id}', 'add_to_favorite_outdoor_area');
        Route::delete('/outdoor/del_faworite_outdoor_region/{article_id}', 'del_faworite_outdoor_region');
    });

    Route::controller(MountController::class)->group( function() {
        Route::post('/mount/edit_mount_massive/{mount_id}', 'edit_mount_massive');
        Route::post('/mount/create_mount_massive', 'create_mount_massive');
    });

    Route::controller(LocalBisnesController::class)->group( function() {
        Route::post('/bisnes/edit_local_bisnes/{bisnes_id}', 'edit_local_bisnes');
        Route::post('/bisnes/add_local_bisnes', 'add_local_bisnes');
        Route::delete('/bisnes/del_local_bisnes/{bisnes_id}', 'del_local_bisnes');
        Route::delete('/bisnes/del_bisnes_article_relation/{article_id}/{bisnes_id}', 'del_bisnes_article_relation');
        Route::delete('/bisnes/del_local_bisnes_image/{image_id}', 'del_local_bisnes_image');
    });

    Route::controller(ArticleGalleryController::class)->group( function() {
        Route::post('/gallery_image', 'get_index_gallery');
    });

    Route::controller(HeadSliderController::class)->group( function() {
        Route::post('/head_slider/add_slide', 'add_slide');
        Route::post('/head_slider/edit_slide/{slide_id}', 'edit_slide');
        Route::delete('/head_slider/del_slide/{slide_id}', 'del_slide');
    });

    Route::controller(SiteDataController::class)->group( function() {
        Route::post('/siteData/edit_site_data', 'edit_site_data');
        Route::post('/siteData/edit_site_global_data', 'edit_site_global_data');
        Route::post('/siteData/edit_site_ka_data', 'edit_site_ka_data');
        Route::post('/siteData/edit_site_ru_data', 'edit_site_ru_data');
        Route::post('/siteData/edit_site_us_data', 'edit_site_us_data');
    });

    Route::controller(SectorController::class)->group( function() {
        Route::post('/sector/add_sector', 'add_sector');
        Route::post('/sector/edit_sector/{sector_id}', 'edit_sector');
        Route::delete('/sector/del_sector/{sector_id}', 'del_sector');
        Route::delete('/sector/del_sector_image_from_db/{image_id}', 'del_sector_image_from_db');
        Route::post('/sector/routes_sequence', 'routes_sequence');
        Route::post('/sector/save_sector_sequence', 'save_sector_sequence');
    });

    Route::controller(SpotRockController::class)->group( function() {
        Route::delete('/spot_rock_images/del_spot_rock_image/{image_id}', 'del_spot_rock_image');
    });

    Route::controller(RouteController::class)->group( function() {
        Route::post('/route/add_route', 'add_route');
        Route::post('/route/edit_route/{route_id}', 'edit_route');
        Route::delete('/route/del_route/{route_id}', 'del_route');
    });

    Route::controller(RouteJsonController::class)->group( function() {
        Route::post('/route_json/add_route_json', 'add_route_json');
        Route::post('/route_json/edit_route_json/{route_id}', 'edit_route_json');
        Route::delete('/route_json/del_route_json/{route_id}', 'del_route_json');
    });

    Route::controller(MTPController::class)->group( function() {
        Route::post('/mtp/mtp_add', 'mtp_add');
        Route::post('/mtp/mtp_edit/{mtp_id}', 'mtp_edit');
        Route::delete('/mtp/del_mtp/{mtp_id}', 'del_mtp');
    });

    Route::controller(MTPPitchController::class)->group( function() {
        Route::post('/mtp/mtp_pitch/mtp_pitch_add', 'mtp_pitch_add');
        Route::post('/mtp/mtp_pitch/mtp_pitch_edit/{pitch_id}', 'mtp_pitch_edit');
        Route::post('/mtp/mtp_pitch/pitchs_sequence', 'pitchs_sequence');
        Route::delete('/mtp/mtp_pitch/del_pitch/{pitch_id}', 'del_pitch');
    });

    Route::controller(SectorLocalImagesController::class)->group( function() {
        Route::post('/sector_local_images/update_image/{image_id}', 'update_image');
        Route::delete('/sector_local_images/del_image_sector_from_db/{image_id}/{sector_id}', 'del_image_sector_from_db');
        Route::post('/sector_local_img/save_canvas_data/{sector_id}', 'save_canvas_data');
    });

    Route::controller(CommentController::class)->group( function() {
        Route::post('/guide_comment/create_comment/{article_id}', 'create_comment');
        Route::post('/guide_comment/confirm_email/{email}', 'confirm_email');
        Route::post('/guide_comment/add_comment_complaint', 'add_comment_complaint');
        Route::post('/guide_comment/make_decision', 'make_decision');
        Route::post('/guide_comment/hide_comment/{comment_id}', 'hide_comment');
        Route::delete('/guide_comment/del_comment/{comment_id}', 'del_comment');
    });

    Route::controller(RoutesReitingController::class)->group( function() {
        Route::post('/route_review/create_route_review/{route_id}', 'create_route_review');
        Route::post('/route_review/edit_route_review/{review_id}', 'edit_route_review');
        Route::delete('/route_review/del_route_review/{review_id}', 'del_route_review');
    });
});

<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\User\Admin\Shop'], function() {
    /*
    *   Product and product categories routes
    */
    Route::controller(ProductController::class)->prefix('set_product')->group( function() {
        // Route::apiResource('/', 'ProductController');
        // Route::get('/get_all_products', 'get_all_products');
        Route::get('/get_user_products', 'get_user_products');
        Route::get('/get_product_editing_data/{product_id}', 'get_product_editing_data');
        Route::get('/add_product', 'add_product');

        Route::post('/edit_product/{product_id}', 'edit_product');
        Route::post('/add_product', 'add_product');

        Route::post('/change_user_relation', 'change_user_relation');
        Route::delete('/del_product/{product_id}', 'del_product');

        Route::get('/get_product_options/{product_id}', 'get_product_options');
        Route::get('/get_current_products', 'get_current_products');

        // });
        // Route::controller(ProductController::class)->group( function() {

        // Route::apiResource('/product', 'ProductController');
        Route::get('/get_local_products/{lang}', 'get_local_products');
        // Route::get('/donation_products/{lang}', 'get_donation_products');
        Route::get('/sale_products/{lang}', 'get_local_saled_products');
        Route::get('/page_product/{lang}/{url_title}', 'get_local_product_in_page');
        Route::get('similar_product/{lang}/{product_id}', 'get_similar_product');
        Route::get('product_price_interval', 'get_product_price_interval');
        Route::get('/get_user_favorite_products', 'get_user_favorite_products');
        Route::get('/get_quick_product/{lang}/{product_id}', 'get_quick_product');
        // Route::get('/get_products_for_custom_order', 'get_products_for_custom_order');


        Route::controller(ProductOptionController::class)->prefix('set_product_option')->group( function() {
            Route::get('/get_all_product_options', 'get_all_product_options');
            Route::get('/get_product_option/{id}', 'get_product_option');
            Route::post('/add_option', 'add_option');
            Route::post('/edit_option/{option_id}', 'edit_option');
            Route::delete('/del_option/{option_id}', 'del_option');
            Route::delete('/del_option_image/{image_id}', 'del_option_image');
        });

        Route::controller(ProductCategoryController::class)->prefix('set_product_category')->group( function() {
            Route::get('/get_editing_product_category/{id}', 'get_editing_product_category');
            Route::post('/add_product_category', 'add_product_category');
            Route::post('/edit_product_category/{id}', 'edit_product_category');
            Route::delete('/del_product_category/{id}', 'del_product_category');

            Route::controller(ProductSubcategoryController::class)->prefix('set_subcategory')->group( function() {
                // Route::get('/get_all_subcategories', 'get_all_subcategories');
                Route::get('/get_subcategories_for_category/{category_id}', 'get_subcategories_for_category');
                Route::get('/get_editing_subcategory/{id}', 'get_editing_subcategory');
                Route::post('/add_subcategory/{id}', 'add_subcategory');
                Route::post('/edit_subcategory/{id}', 'edit_subcategory');
                Route::delete('/del_subcategory/{id}', 'del_subcategory');
            });
        });

        Route::controller(ProductBrandController::class)->prefix('set_brand')->group( function() {
            // Route::get('/get_all_brands', 'get_all_brands');
            Route::get('/get_editing_brand/{id}', 'get_editing_brand');
            Route::post('/create_brand', 'create_brand');
            Route::post('/edit_brand/{id}', 'edit_brand');
            Route::delete('/del_brand/{id}', 'del_brand');
        });

        /*
        *   Product Coments routes
        */
        Route::controller(ProductFeedbackController::class)->prefix('set_product_feedback')->group( function() {
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

        Route::controller(SaleCodeController::class)->prefix('set_sale_code')->group( function() {
            // Route::get('/get_all_sale_code', 'get_all_sale_code');
            Route::post('/add_sale_code', 'add_sale_code');
            Route::get('/get_editing_sale_code/{sale_code_id}', 'get_editing_sale_code');
            Route::post('/edit_sale_code/{sale_code_id}', 'edit_sale_code');
            Route::delete('/del_sale_code/{sale_code_id}', 'del_sale_code');
        });
    });
    /*
    *   Warehouse
    */
    Route::controller(WarehouseController::class)->prefix('get_warehouse')->group( function() {
        Route::get('/get_warehouses', 'get_warehouses');
        Route::get('/get_warehouse_data/{id}', 'get_warehouse_data');
        Route::get('/get_editing_warehouse/{id}', 'get_editing_warehouse');
        Route::get('/get_activ_warehouse/{id}', 'get_activ_warehouse');
        Route::get('/get_warehouse_product_options/{id}', 'get_warehouse_product_options');
        Route::get('/get_warehouse_product_options_grouped/{id}', 'get_warehouse_product_options_grouped_by_product');
        Route::get('/get_product_option_details/{id}/{product_option_id}', 'get_product_option_details');
    });

    Route::controller(WarehouseController::class)->prefix('set_warehouse')->group( function() {
        // Product option management in warehouses
        Route::post('/add_warehouse', 'add_warehouse');
        Route::post('/edit_warehouse/{id}', 'edit_warehouse');
        Route::delete('/del_warehouse/{id}', 'del_warehouse');
        Route::post('/add_product_option_to_warehouse/{id}', 'add_product_option_to_warehouse');
        Route::post('/edit_product_option_quantity/{id}/{product_option_id}', 'edit_product_option_quantity');
        Route::delete('/delete_product_option_from_warehouse/{id}/{product_option_id}', 'delete_product_option_from_warehouse');
        Route::post('/migrate_product_option/{id}/{product_option_id}', 'migrate_product_option');
    });
    
    Route::controller(TourController::class)->prefix('set_tour')->group( function() {
        // Route::get('/get_all_tours', 'get_all_tours');
        // Route::get('/get_tour/{id}', 'get_tour');
        // Route::get('/get_tour_images/{id}', 'get_tour_images');
        Route::get('/get_editing_tour/{tour_id}', 'get_editing_tour');
        Route::post('/add_tour', 'add_tour');
        Route::post('/edit_tour/{tour_id}', 'edit_tour');
        Route::post('/change_user_relation', 'change_user_relation');
        Route::delete('/del_tour_image/{image_id}', 'del_tour_image');
        Route::delete('/del_tour/{tour_id}', 'del_tour');

        Route::controller(TourCategoryController::class)->prefix('set_category')->group( function() {
            // Route::get('/get_all_categories', 'get_all_categories');
            // Route::get('/get_category/{id}', 'get_category');
            Route::post('/add_category', 'add_category');
            Route::post('/edit_category/{category_id}', 'edit_category');
            Route::delete('/del_category/{category_id}', 'del_category');
            Route::get('/get_editing_category/{category_id}', 'get_editing_category');
        });

        Route::controller(TourReservationController::class)->prefix('set_reservation')->group( function() {
            // Route::get('/get_all_reservations', 'get_all_reservations');
            // Route::get('/get_reservation/{id}', 'get_reservation');
            Route::post('/create_reservation/{tour_id}', 'create_reservation');
            Route::post('/verifiation_reservation/{reservation_id}', 'verifiation_reservation');
            Route::delete('/del_reservation/{reservation_id}', 'del_reservation');

            // Google Calendar Sync Routes
            Route::prefix('google-calendar')->group( function() {
                Route::post('/init-auth', 'initGoogleAuth');
                Route::post('/handle-callback', 'handleGoogleCallback');
                Route::post('/disconnect', 'disconnectGoogleCalendar');
                Route::post('/sync', 'syncToGoogleCalendar');
                Route::get('/status', 'getGoogleCalendarStatus');
            });
        });


        Route::controller(TourReservationController::class)->prefix('get_reservation')->group( function() {
            Route::get('/get_user_reservations', 'get_user_reservations');

            Route::get('/get_all_reservations', 'get_all_reservations');
            Route::get('/get_reservation/{id}', 'get_reservation');

            // Editing routes moved to adminAction.php
        });
    });

    Route::controller(ServicesController::class)->prefix('set_service')->group( function() {
        // Route::apiResource('/', 'ServicesController');
        Route::get('/get_editing_service/{service_id}', 'get_editing_service');
        // Route::get('/get_service_images/{service_id}', 'get_service_images');
        // Route::get('/get_service/{service_id}', 'get_service');
        Route::post('/add_service', 'add_service');
        Route::post('/edit_service/{service_id}', 'edit_service');
        Route::delete('/del_service/{service_id}', 'del_service');
        Route::delete('/del_service_image/{image_id}', 'del_service_image');

        // Route::get('/{lang}/{url_title}', 'get_local_service_in_page');
    });
    // Route::get('/services/{lang}', 'ServicesController@get_local_services');

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

    Route::controller(OrderController::class)->prefix('set_order')->group( function() {
        Route::get('/get_all_orders', 'get_all_orders');
        Route::post('/create_order', 'create_order');
        Route::post('/add_custom_order', 'add_custom_order');

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

        Route::get('/get_order_statistics/{period}', 'get_order_statistics');
    });
    // Route::apiResource('/sale_code', 'SaleCodeController');

    /*
    *   Cart and orders routes
    */
    Route::controller(ShipedRegionController::class)->prefix('set_shiped_region')->group( function() {
        Route::get('/get_all_shiped_regions', 'get_all_shiped_regions');
        Route::get('/get_activ_region/{region_id}', 'get_activ_region');
        Route::post('/add_region', 'add_region');
        Route::post('/edit_region/{region_id}', 'edit_region');
        Route::delete('/del_region/{region_id}', 'del_region');
    });

});

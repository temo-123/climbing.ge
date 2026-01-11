<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api\Shop'], function() {
    /*
    *   Product and product categories routes
    */
    Route::controller(ProductController::class)->prefix('get_product')->group( function() {
        // Route::apiResource('/', 'ProductController');
        Route::get('/get_products_for_index/{lang}', 'get_products_for_index');
        Route::get('/get_all_products', 'get_all_products');
        Route::get('/get_user_products', 'get_user_products');
        // Route::get('/get_product_editing_data/{product_id}', 'get_product_editing_data');
        // Route::get('/add_product', 'add_product');

        // Editing routes moved to adminAction.php

        Route::get('/get_product_options/{product_id}', 'get_product_options');
        Route::get('/get_current_products', 'get_current_products');
    // });

    // Route::controller(ProductController::class)->group( function() {
        // Route::apiResource('/product', 'ProductController');
        Route::get('/get_local_products/{lang}', 'get_local_products');
        Route::get('/get_donation_products/{lang}', 'get_donation_products');
        Route::get('/get_local_product_in_page/{lang}/{url_title}', 'get_local_product_in_page');
        Route::get('/get_similar_product/{lang}/{product_id}', 'get_similar_product');
        Route::get('/get_product_price_interval', 'get_product_price_interval');
        Route::get('/get_user_favorite_products', 'get_user_favorite_products');
        Route::get('/get_quick_product/{lang}/{product_id}', 'get_quick_product');
        // Route::get('/get_products_for_custom_order', 'get_products_for_custom_order');

        Route::get('/get_local_saled_products/{lang}', 'get_local_saled_products');

    // Route::apiResource('/product_category', 'ProductCategoryController');


        Route::controller(ProductBrandController::class)->prefix('get_brand')->group( function() {
            Route::get('/get_all_brands', 'get_all_brands');
            Route::get('/get_brand/{id}', 'get_brand');
            // Editing routes moved to adminAction.php
        });

        Route::controller(ProductOptionController::class)->prefix('get_product_option')->group( function() {
            Route::get('/get_activ_product_options/{product_id}', 'get_activ_product_options');
            Route::get('/get_editing_option/{option_id}', 'get_editing_option');
        });

        Route::controller(ProductCategoryController::class)->prefix('get_product_category')->group( function() {
            Route::get('/get_all_product_category', 'get_all_product_category');
            // Route::get('/get_subcategories_for_category/{category_id}', 'get_subcategories_for_category');
            // Route::get('/get_subcategory/{id}', 'get_subcategory');
            // Editing routes moved to adminAction.php

            Route::controller(ProductSubcategoryController::class)->prefix('get_subcategory')->group( function() {
                Route::get('/get_all_subcategories', 'get_all_subcategories');
                Route::get('/get_subcategories_for_category/{category_id}', 'get_subcategories_for_category');
                Route::get('/get_subcategory/{id}', 'get_subcategory');
                // Editing routes moved to adminAction.php
            });
        });

        Route::controller(ProductFeedbackController::class)->prefix('get_product_feedback')->group( function() {
            Route::get('/get_product_feedbacks/{product_id}', 'get_product_feedbacks');
        });
    });

    Route::controller(ProductFeedbackController::class)->prefix('set_product_feedback_by_gest')->group( function() {
        Route::post('/create_feedback/{product_id}', 'create_feedback');
        Route::post('/confirm_email/{email}', 'confirm_email');
    });


    Route::controller(UserTourReservationController::class)->prefix('set_user_reservation')->group( function() {
        // Route::get('/get_all_reservations', 'get_all_reservations');
        // Route::get('/get_reservation/{id}', 'get_reservation');
        Route::post('/create_reservation/{tour_id}', 'create_reservation');
        // Route::post('/verifiation_reservation/{reservation_id}', 'verifiation_reservation');
        // Route::delete('/del_reservation/{reservation_id}', 'del_reservation');
    });
    /*
    *   Tours and tour categories routes
    */
    Route::controller(TourController::class)->prefix('get_tour')->group( function() {
        Route::get('/get_tours_for_index/{lang}', 'get_tours_for_index');
        Route::get('/get_all_tours/{lang}', 'get_all_tours'); // get_tours
        Route::get('/get_all_tours', 'get_all_tours');
        Route::get('/get_user_tours', 'get_user_tours');
        Route::get('/get_similar_tours/{lang}/{tour_id}', 'get_similar_tours');
        Route::get('/get_tour/{lang}/{url_title}', 'get_tour');

        // Route::get('/get_editing_tour/{tour_id}', 'get_editing_tour');
        Route::get('/get_tour_images/{tour_id}', 'get_tour_images');

        Route::controller(TourCategoryController::class)->prefix('get_category')->group( function() {
            Route::get('/get_all_categories', 'get_all_categories');
            Route::get('/get_category/{id}', 'get_category');

            // Route::get('/get_editing_category/{category_id}', 'get_editing_category');
        });
    });

    /*
    *   Services routes
    */
    Route::controller(ServicesController::class)->prefix('get_service')->group( function() {
        // Route::apiResource('/', 'ServicesController');
        Route::get('/get_services_for_index/{lang}', 'get_services_for_index');
        Route::get('/get_all_services', 'get_all_services');
        Route::get('/get_service_images/{service_id}', 'get_service_images');
        Route::get('/get_service/{service_id}', 'get_service');

        Route::get('/get_local_services/{lang}', 'get_local_services');
        Route::get('/get_similar_services/{lang}/{id}', 'get_similar_service');

        Route::get('/get_local_service_in_page/{lang}/{url_title}', 'get_local_service_in_page');
    });
    // Route::get('/services/{lang}', 'ServicesController@get_local_services');
    // Route::get('/similar_services/{lang}/{id}', 'ServicesController@get_similar_service');

    /*
    *   Cart and orders routes
    */
    Route::controller(CartController::class)->group( function() {
        Route::apiResource('/cart', 'CartController');
        // Editing routes moved to adminAction.php
    });

    // Route::controller(OrderController::class)->prefix('get_order')->group( function() {
    //     Route::get('/get_all_orders', 'get_all_orders');
    //     Route::get('/get_order_status/{order_id}', 'get_order_status');
    //     Route::get('/get_user_purchases', 'get_user_purchases');
    //     Route::get('/get_user_orders', 'get_user_orders');
    //     Route::get('/get_user_purchules', 'get_user_purchules');
    //     Route::get('/get_activ_order/{order_id}', 'get_activ_order');
    //     Route::get('/get_order_detals/{order_id}', 'get_order_detals');
    //     Route::get('/get_order_products/{order_id}', 'get_order_products');
    //     Route::get('/is_order_confirm/{order_id}', 'is_order_confirm');

    //     Route::get('/get_order_statistics/{period}', 'get_order_statistics');
    // });

    Route::controller(SaleCodeController::class)->prefix('get_sale_code')->group( function() {
        Route::get('/get_all_sale_code', 'get_all_sale_code');
    });
    // Route::apiResource('/sale_code', 'SaleCodeController');

    /*
    *   Cart and orders routes
    */
    Route::controller(ShipedRegionController::class)->prefix('get_shiped_region')->group( function() {
        Route::get('/get_all_shiped_regions', 'get_all_shiped_regions');
        Route::get('/get_activ_region/{region_id}', 'get_activ_region');
        // Editing routes moved to adminAction.php
    });

});

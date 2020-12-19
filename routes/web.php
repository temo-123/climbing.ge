<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LocalisationService::locale(),'middleware' => 'setLocale'], function() {
    Route::domain("climbing.loc")->group(function () {
        Route::group(['namespace'=>'Site'], function() {
            
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/about_us', 'AboutController@index')->name('about_us_page');

            Route::get('/news/{title}', ['uses'=>'NewsController@news_page', 'as'=>'news_page']);
            Route::get('/security/{title}', ['uses'=>'SecurityController@security_page', 'as'=>'security_page']);
            Route::get('event/{title}', ['uses'=>'EventController@events_page', 'as'=>'events_page']);

            Route::get('/outdoor', 'OutdoorController@outdoor_list')->name('outdoor_list');
            Route::get('/outdoor/{title}', ['uses'=>'OutdoorController@outdoor_page', 'as'=>'outdoor_page']);

            Route::get('/indoor', 'IndoorController@indoor_list')->name('indoor_list');
            Route::get('/indoor/{title}', ['uses'=>'IndoorController@indoor_page', 'as'=>'indoor_page']);

            Route::get('/mountaineering', 'MountController@mount_list')->name('mount_list');
            Route::get('/mountaineering/{title}', ['uses'=>'MountController@mount_page', 'as'=>'mount_page']);

            Route::get('/ice_and_mix', 'IceController@ice_list')->name('ice_list');
            Route::get('/ice/{title}', ['uses'=>'IceController@ice_page', 'as'=>'ice_page']);

            Route::get('/other', 'OtherActivityController@other_list')->name('other_list');
            Route::get('/other/{title}', ['uses'=>'OtherActivityController@other_page', 'as'=>'other_page']);

            Route::get('/partniors/{title}', ['uses'=>'PartnersController@partners_page', 'as'=>'partners_page']);

            Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
            Route::get('/sitemap', 'SitemapController@sitemap');
        
            Route::group(['namespace'=>'App'], function() {

                Route::get('/events_interes/{events_id}/{actions}', ['uses'=>'InterestedEventController@events_interes', 'as'=>'events_interes']);
                
                Route::post('/search', 'SearchController@search');
                Route::get('members/search', 'SearchController@index');

                Route::post('/send_message', 'MessageController@send');

                Route::any('/add_comment', 'CommentController@add_comment');
                Route::get('/get_comments/{article_id}', "CommentController@get_comments");

                Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
                Route::get('/sitemap', 'SitemapController@sitemap');
            });
        });
    });

    Route::domain('user.climbing.loc')/*->middleware('verified')*/->group(function () {
        Route::group(['middleware'=>'auth'], function() {
            Route::group(['namespace'=>'User'], function() {
        
                Route::get('/', 'IndexController@index')->name('user_index');

                    Route::group(['prefix'=>'mountaineering', 'namespace'=>'Mountaineering'], function() {
                        Route::get('/', ['uses'=>'MountListController@index', 'as'=>'mountaineering']);
                        Route::any('/get_mount_data', 'MountListController@get_mount_data');
                        Route::any('/get_mount_route_data', 'MountListController@get_mount_route_data');

                            Route::match(['get','post'], '/mount_add', ['uses'=>'MountMassiveController@add','as'=>'mountAdd']);
                            Route::match(['get', 'post', 'delete'], '/mount_edit/{mount}', ['uses' => 'MountMassiveController@edit', 'as'=>'mountEdit']);
                            Route::match(['get', 'post', 'delete'], '/mount_delete/{mount}', ['uses' => 'MountMassiveController@delete', 'as'=>'mountDel']);
                        
                        // Route::match(['get','post'], '/mount_route_add', ['uses'=>'MountRouteController@store','as'=>'mountRouteAdd']);
                        // Route::match(['get', 'post', 'delete'], '/mount_route_edit/{mount_route}', ['uses' => 'MountRouteController@edit', 'as'=>'mountRouteEdit']);
                    });
                    

                    Route::group(['prefix'=>'routes_and_sectors', 'namespace'=>'Route_And_Sector'], function() {
                        Route::get('/', ['uses'=>'RoutesListController@index', 'as'=>'routes_and_sectors']);
                        Route::match(['post'],'/routesNumEdit', ['uses'=>'RoutesListController@store', 'as'=>'routesNumEdit']);
                        Route::any('/get_region_data', 'RoutesListController@get_region_data');
                        
                        Route::match(['get','post'], '/sector_add', ['uses'=>'SectorController@add','as'=>'sectorAdd']);
                        Route::match(['get', 'post', 'delete'], '/sector_edit/{id}', ['uses' => 'SectorController@edit', 'as'=>'sectorEdit']);
                        Route::match(['get', 'post', 'delete'], '/sector_delete/{id}', ['uses' => 'SectorController@delete', 'as'=>'sectorDel']);
                        Route::any('/get_sector_data', 'RoutesListController@get_sector_data');
                        
                        Route::match(['get','post'], '/route_add', ['uses'=>'RoutesController@add','as'=>'routeAdd']);
                        Route::match(['get', 'post', 'delete'], '/route_edit/{id}', ['uses' => 'RoutesController@edit', 'as'=>'routeEdit']);
                        Route::match(['get', 'post', 'delete'], '/route_delete/{id}', ['uses' => 'RoutesController@delete', 'as'=>'routeDel']);
                        Route::any('/get_route_data', 'RoutesListController@get_route_data');

                        Route::match(['get','post'], '/mtp_add', ['uses'=>'MtpController@add_mtp','as'=>'mtpAdd']);
                        Route::match(['get', 'post', 'delete'], '/mtp_edit/{id}', ['uses' => 'MtpController@edit_mtp', 'as'=>'mtpEdit']);
                        Route::match(['get', 'post', 'delete'], '/mtp_delete/{id}', ['uses' => 'MtpController@delete_mtp', 'as'=>'mtpDelete']);
                        Route::any('/get_mtp_data', 'RoutesListController@get_mtp_data');

                        Route::match(['get','post'], '/mtp_pitch_add', ['uses'=>'MtpController@add_pitch','as'=>'mtpPitchAdd']);
                        Route::match(['get', 'post', 'delete'], '/mtp_pitch_edit/{id}', ['uses' => 'MtpController@edit_pitch', 'as'=>'mtpPitchEdit']);
                        Route::match(['get', 'post', 'delete'], '/mtp_pitch_delete/{id}', ['uses' => 'MtpController@delete_pitch', 'as'=>'mtpPitchDel']);
                        Route::any('/get_mtp_pitch_data', 'RoutesListController@get_mtp_pitch_data');
                    }); 
            

                    Route::group(['prefix'=>'articles', 'namespace'=>'Article'], function() {
                        Route::any('/get_article_data/{article_category}', 'ArticleController@get_article_data');

                        Route::get('/{article_category}', ['uses'=>'ArticleController@index', 'as'=>'article_list']);
                        Route::match(['get','post'], '/add/{category}', ['uses'=>'ArticleController@add_global_article','as'=>'articleAdd']);
                        Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'ArticleController@edit_global_article', 'as'=>'articleEdit']);
                        
                        Route::match(['get', 'post', 'HEAD'], '/upload_image', ['uses' => 'ArticleController@image_upload', 'as'=>'articleImageUpload']);

                        Route::match(['get','post'], '/us/add/{category}', ['uses'=>'UsArticleController@add_us_article','as'=>'usArticleAdd']);
                        Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsArticleController@edit_us_article', 'as'=>'usArticleEdit']);

                        Route::match(['get','post'], '/ru/add/{category}', ['uses'=>'RuArticleController@add_ru_article','as'=>'ruArticleAdd']);
                        Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuArticleController@edit_ru_article', 'as'=>'ruArticleEdit']);

                        Route::match(['get','post'], '/ka/add/{category}', ['uses'=>'KaArticleController@add_ka_article','as'=>'kaArticleAdd']);
                        Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaArticleController@edit_ka_article', 'as'=>'kaArticleEdit']);

                        Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ArticleController@delete', 'as'=>'articleDel']);
                    });    

                    
                    Route::group(['prefix'=>'products'], function() {
                        Route::get('/', ['uses'=>'ProductsController@index', 'as'=>'products_list']);
                        Route::match(['get','post'], '/add', ['uses'=>'ProductsController@store','as'=>'productsAdd']);
                        Route::match(['get', 'post'], '/edit/{product}', ['uses' => 'ProductsController@edit', 'as'=>'productsEdit']); 
                        Route::match(['delete'], '/delete/{product}', ['uses' => 'ProductsController@delete', 'as'=>'productsDel']);
                        Route::get('/favorite', ['uses'=>'ProductsController@favorite', 'as'=>'favorite']);
                    }); 


                    Route::group(['prefix'=>'gallery'], function() {
                        Route::get('/', ['uses'=>'GalleryController@index', 'as'=>'gallery_list']);
                        Route::match(['get','post'], '/add', ['uses'=>'GalleryController@add','as'=>'galleryAdd']);
                        Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'GalleryController@edit', 'as'=>'galleryEdit']);
                        Route::match(['get', 'post', 'delete'], '/delete/{id}', ['uses' => 'GalleryController@delete', 'as'=>'galleryDel']);
                    });
                   
            
                    Route::group(['prefix'=>'options'], function() {
                        Route::get('/', ['uses'=>'UserController@options_index', 'as'=>'options']);
                        Route::patch('/user-update/{user}', 'UserController@edit');
                        Route::any('/get_user_data', 'UserController@get_user_data');
                    });


                    Route::group(['prefix'=>'users'], function() {
                        Route::get('/', ['uses'=>'UserController@users_list', 'as'=>'users']);
                        Route::match(['delete'], '/edit/{user}', ['uses' => 'UserController@destroy', 'as'=>'userDel']);
                    });


                    Route::group(['prefix'=>'reviews_and_comments'], function() {
                        Route::get('/', ['uses'=>'CommentsController@index', 'as'=>'comments_list']);
                        Route::any('/get_my_comment_data', 'CommentsController@get_my_comments_data');
                        Route::any('/get_all_comment_data', 'CommentsController@get_all_comments_data');
                        Route::match(['post', 'delete'], '/delete_comment/{id}', ['uses' => 'CommentsController@delete', 'as'=>'commentDel']);
                    });


                    Route::group(['prefix'=>'about'], function() {
                        Route::get('/', ['uses'=>'AboutController@index', 'as'=>'about']);
                        Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'AboutController@edit', 'as'=>'siteInfoEdit']);
                    });
            });
        });
    });

    Route::domain('shop.climbing.loc')->group(function () {
        Route::group(['namespace'=>'Shop'], function() {
            Route::get('/', 'IndexController@index')->name('shop_index');

            // Route::get('/', 'ShopController@shop_list')->name('shop_list');
            Route::get('/product/{title}', ['uses'=>'ProductPageController@shop_page', 'as'=>'shop_page']);
            Route::get('/seller/{id}', ['uses'=>'SellerController@seller_page', 'as'=>'seller_page']);

            Route::get('/favorite_product/{product_id}/{actions}', ['uses'=>'PrioritiesController@favorite_product', 'as'=>'favorite_product']);

            Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
            Route::get('/sitemap', 'SitemapController@sitemap');
        });
    });
});

Route::get('/redirect', 'Auth\SocialAuthFacebookController@redirect');
Route::get('/callback', 'Auth\SocialAuthFacebookController@callback');

Auth::routes(['verify' => true]);
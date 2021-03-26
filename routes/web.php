<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => LocalisationService::locale(),'middleware' => 'setLocale'], function() {
    Route::domain(config('app.url'))->group(function () {
        Route::group(['namespace'=>'Site'], function() {
            
            Route::get('/', 'IndexController@index')->name('index');
            Route::get('/about_us', 'AboutController@index')->name('about_us_page');

            Route::get('/news/{title}', ['uses'=>'NewsController@news_page', 'as'=>'news_page']);
            Route::get('event/{title}', ['uses'=>'EventController@events_page', 'as'=>'events_page']);

            Route::get('/security/{title}', ['uses'=>'TechTipsController@security_page', 'as'=>'security_page']);
            Route::any('/get_security_data', 'TechTipsController@get_security_data');

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
        
            Route::group(['namespace'=>'App'], function() {

                Route::get('/events_interes/{events_id}/{actions}', ['uses'=>'InterestedEventController@events_interes', 'as'=>'events_interes']);
                
                Route::post('/search', 'SearchController@search');
                Route::get('members/search', 'SearchController@index');

                Route::post('/send_message', 'MessageController@send');

                Route::any('/add_comment', 'CommentController@add_comment');
                Route::get('/get_comments/{article_id}', "CommentController@get_comments");

                Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
                Route::get('/sitemap', 'SitemapController@sitemap_xml');
                Route::get('/robots.txt', 'SitemapController@robots');
            });
        });
    });
    
    Route::domain('shop.' . config('app.url'))->group(function () {
        Route::group(['namespace'=>'Shop'], function() {
            Route::get('/', 'IndexController@index')->name('shop_index');
            Route::get('/about_us', ['uses'=>'About_usController@about_us', 'as'=>'about_shop']);

            Route::get('/product/{title}', ['uses'=>'ProductPageController@shop_page', 'as'=>'shop_page']);
            
            Route::get('/favorite_product/{product_id}/{actions}', ['uses'=>'App\PrioritiesController@favorite_product', 'as'=>'favorite_product']);

            Route::group(['namespace'=>'App'], function() {
                Route::get('/sitemap.xml', 'SitemapController@sitemap_xml');
                Route::get('/sitemap', 'SitemapController@sitemap_xml');
                Route::get('/robots.txt', 'SitemapController@robots');

                Route::post('/send_message', 'MessageController@send');
                Route::post('/search', 'SearchController@search');
            });
        });
    });

    Route::domain('user.' . config('app.url'))/*->middleware('verified')*/->group(function () {
        Route::group(['middleware'=>'auth'], function() {
            Route::group(['namespace'=>'User'], function() {
        
                Route::get('/', 'IndexController@index')->name('user_index');

                    Route::group(['prefix'=>'mountaineering', 'namespace'=>'Mountaineering'], function() {
                        Route::get('/', ['uses'=>'MountListController@index', 'as'=>'mountaineering']);

                        Route::any('/get_mount_data', 'MountListController@get_mount_data');
                        Route::any('/get_mount_route_data', 'MountListController@get_mount_route_data');

                        Route::any('/get_mount_editing_data/{id}', 'MountMassiveController@get_mount_editing_data');

                        Route::match(['get','post'], '/mount_add_form', ['uses'=>'MountMassiveController@add_form','as'=>'mountAddForm']);
                        Route::match(['get','post'], '/mount_add', ['uses'=>'MountMassiveController@add','as'=>'mountAdd']);
                        Route::match(['get', 'post', 'delete'], '/mount_edit_form/{id}', ['uses' => 'MountMassiveController@edit_form', 'as'=>'mountEditForm']);
                        Route::match(['get', 'post', 'delete'], '/mount_edit/{id}', ['uses' => 'MountMassiveController@edit', 'as'=>'mountEdit']);
                        Route::match(['get', 'post', 'delete'], '/mount_delete/{id}', ['uses' => 'MountMassiveController@delete', 'as'=>'mountDel']);
                    });
                    
                    Route::group(['prefix'=>'routes_and_sectors', 'namespace'=>'Route_And_Sector'], function() {
                        Route::get('/', ['uses'=>'RoutesListController@index', 'as'=>'routes_and_sectors']);
                        Route::match(['post'],'/routesNumEdit', ['uses'=>'RoutesListController@store', 'as'=>'routesNumEdit']);
                        Route::any('/get_region_data', 'RoutesListController@get_region_data');
                        
                        Route::match(['get','post'], '/sector_add_form', ['uses'=>'SectorController@add_form','as'=>'sectorAddForm']);
                        Route::match(['get','post'], '/sector_add', ['uses'=>'SectorController@add','as'=>'sectorAdd']);
                        Route::match(['get', 'post', 'delete'], '/sector_edit_form/{id}', ['uses' => 'SectorController@edit_form', 'as'=>'sectorEditForm']);
                        Route::match(['get', 'post', 'delete'], '/sector_edit/{id}', ['uses' => 'SectorController@edit', 'as'=>'sectorEdit']);
                        Route::match(['get', 'post', 'delete'], '/sector_delete/{id}', ['uses' => 'SectorController@delete', 'as'=>'sectorDel']);
                        
                        Route::any('/get_sector_editing_data/{id}', 'SectorController@get_sector_editing_data');
                        Route::any('/get_sector_image/{sector_id}', 'SectorController@get_sector_image');
                        Route::any('/get_sector_data', 'RoutesListController@get_sector_data');
                        Route::any('/get_temporary_sector_editing_data', 'SectorController@get_temporary_sector_editing_data');

                        Route::any('/create_temporary_sector', 'SectorController@create_temporary_sector');
                        Route::any('/delete_temporary_sector/{sector_id}', 'SectorController@delete_temporary_sector');

                        Route::match(['get', 'post'], '/upload_sector_image/{sector_id}', ['uses' => 'SectorController@sector_image_upload', 'as'=>'sectorImageUpload']);
                        Route::match(['get', 'post'], '/update_sector_image/{id}', ['uses' => 'SectorController@sector_image_update', 'as'=>'sectorImageUpdate']);
                        Route::match(['get', 'post'], '/delete_sector_image/{id}', ['uses' => 'SectorController@sector_image_delete', 'as'=>'sectorImageDelete']);
                        

                        Route::match(['get','post'], '/route_add', ['uses'=>'RoutesController@add','as'=>'routeAdd']);
                        Route::match(['get', 'post', 'delete'], '/route_edit_form/{id}', ['uses' => 'RoutesController@edit_form', 'as'=>'routeEditForm']);
                        Route::match(['get', 'post', 'delete'], '/route_edit/{id}', ['uses' => 'RoutesController@edit', 'as'=>'routeEdit']);
                        Route::match(['get', 'post', 'delete'], '/route_delete/{id}', ['uses' => 'RoutesController@delete', 'as'=>'routeDel']);
                        Route::any('/get_route_editing_data/{id}', 'RoutesController@get_route_editing_data');
                        Route::any('/get_route_data', 'RoutesListController@get_route_data');

                        Route::match(['get','post'], '/mtp_add', ['uses'=>'MtpController@add_mtp','as'=>'mtpAdd']);
                        Route::match(['get', 'post', 'delete'], '/mtp_edit_form/{id}', ['uses' => 'MtpController@edit_mtp_form', 'as'=>'mtpEditForm']);
                        Route::match(['get', 'post', 'delete'], '/mtp_edit/{id}', ['uses' => 'MtpController@edit_mtp', 'as'=>'mtpEdit']);
                        Route::match(['get', 'post', 'delete'], '/mtp_delete/{id}', ['uses' => 'MtpController@delete_mtp', 'as'=>'mtpDelete']);
                        Route::any('/get_mtp_editing_data/{id}', 'MtpController@get_mtp_editing_data');
                        Route::any('/get_mtp_data', 'RoutesListController@get_mtp_data');

                        Route::match(['get','post'], '/mtp_pitch_add', ['uses'=>'MtpController@add_pitch','as'=>'mtpPitchAdd']);
                        Route::match(['get', 'post', 'delete'], '/mtp_pitch_edit_form/{id}', ['uses' => 'MtpController@edit_pitch_form', 'as'=>'mtpPitchEditForm']);
                        Route::match(['get', 'post', 'delete'], '/mtp_pitch_edit/{id}', ['uses' => 'MtpController@edit_pitch', 'as'=>'mtpPitchEdit']);
                        Route::match(['get', 'post', 'delete'], '/mtp_pitch_delete/{id}', ['uses' => 'MtpController@delete_pitch', 'as'=>'mtpPitchDel']);
                        Route::any('/get_mtp_pitch_editing_data/{id}', 'MtpController@get_mtp_pitch_editing_data');
                        Route::any('/get_mtp_pitch_data', 'RoutesListController@get_mtp_pitch_data');
                    }); 
            

                    Route::group(['prefix'=>'articles', 'namespace'=>'Article'], function() {
                        Route::any('/create_temporary_article/{category}', 'ArticleController@create_temporary_article');
                        Route::any('/del_temporary_article/{id}', 'ArticleController@delete');
                        Route::any('/get_temporary_article_editing_data', 'ArticleController@get_temporary_article_editing_data');

                        Route::match(['get','post'], '/global/add/{category}', ['uses'=>'GlobalArticleController@add_global_article','as'=>'globalArticleAdd']);
                        Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalArticleController@edit_global_article', 'as'=>'globalArticleEdit']);

                        Route::match(['get','post'], '/us/add/{category}', ['uses'=>'UsArticleController@add_us_article','as'=>'usArticleAdd']);
                        Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsArticleController@edit_us_article', 'as'=>'usArticleEdit']);

                        Route::match(['get','post'], '/ru/add/{category}', ['uses'=>'RuArticleController@add_ru_article','as'=>'ruArticleAdd']);
                        Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuArticleController@edit_ru_article', 'as'=>'ruArticleEdit']);

                        Route::match(['get','post'], '/ka/add/{category}', ['uses'=>'KaArticleController@add_ka_article','as'=>'kaArticleAdd']);
                        Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaArticleController@edit_ka_article', 'as'=>'kaArticleEdit']);

                        Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ArticleController@delete', 'as'=>'articleDel']);



                        Route::get('/{article_category}', ['uses'=>'ArticleController@index', 'as'=>'article_list']);

                        Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'ArticleController@edit_article_page', 'as'=>'articleEdit']);
                        Route::match(['get', 'post'], '/add/{category}', ['uses' => 'ArticleController@add_article_page', 'as'=>'articleAdd']);

                        Route::match(['get', 'post', 'HEAD'], '/upload_image', ['uses' => 'GlobalArticleController@image_upload', 'as'=>'articleImageUpload']);
                        Route::match(['get', 'post', 'HEAD'], '/update_image/{id}', ['uses' => 'GlobalArticleController@image_update', 'as'=>'articleImageUpdate']);

                        Route::any('/get_article_data/{article_category}', 'ArticleController@get_article_data');
                        Route::any('/get_editing_data/{id}', 'ArticleController@get_editing_data');
                    });    

                    
                    Route::group(['prefix'=>'products', 'namespace'=>'Product'], function() {
                        Route::any('/create_temporary_product', 'ProductController@create_temporary_product');
                        Route::any('/del_temporary_product/{id}', 'ProductController@del_temporary_product');

                        Route::match(['get','post'], '/add_product', ['uses'=>'ProductController@add_product_page','as'=>'productAddPage']);
                        Route::match(['get', 'post'], '/edit_product/{id}', ['uses' => 'ProductController@edit_product_page', 'as'=>'productEditPage']);

                        Route::match(['get','post'], '/global/add/', ['uses'=>'GlobalProductController@add_global_product','as'=>'globalProductAdd']);
                        Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalProductController@edit_global_product', 'as'=>'globalProductEdit']);

                        Route::match(['get','post'], '/us/add/', ['uses'=>'UsProductController@add_us_product','as'=>'usProductAdd']);
                        Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsProductController@edit_us_product', 'as'=>'usProductEdit']);

                        Route::match(['get','post'], '/ru/add/', ['uses'=>'RuProductController@add_ru_product','as'=>'ruProductAdd']);
                        Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuProductController@edit_ru_product', 'as'=>'ruProductEdit']);

                        Route::match(['get','post'], '/ka/add/', ['uses'=>'KaProductController@add_ka_product','as'=>'kaProductAdd']);
                        Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaProductController@edit_ka_product', 'as'=>'kaProductEdit']);

                        Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ProductController@del_product', 'as'=>'productDel']);

                        Route::get('/', ['uses'=>'ProductController@product_list_page', 'as'=>'products_list']);
                        Route::any('/get_product_data', 'ProductController@get_product_data');
                        Route::any('/get_product_editing_data/{editing_product_id}', 'ProductController@get_product_editing_data');
                        Route::any('/get_temporary_product_editing_data', 'ProductController@get_temporary_product_editing_data');
                        Route::any('/get_product_image/{product_id}', 'ProductController@get_product_image');

                        Route::any('/upload_product_image/{product_id}', 'ProductController@upload_product_image');
                        Route::any('/del_product_image/{image_id}', 'ProductController@del_product_image');
                        Route::any('/update_product_image/{id}', 'ProductController@update_product_image');

                        Route::match(['get','post'], '/category/add/form/', ['uses'=>'CategoryController@product_add_form','as'=>'categoryAddForm']);
                        Route::match(['get','post'], '/category/add/', ['uses'=>'CategoryController@add_product_category','as'=>'categoryAdd']);
                        Route::match(['get', 'post'], '/category/edit/form/{id}', ['uses' => 'CategoryController@product_edit_form', 'as'=>'categoryEditForm']);
                        Route::match(['get', 'post'], '/category/edit/{id}', ['uses' => 'CategoryController@edit_product_category', 'as'=>'categoryEdit']);
                        Route::match(['post', 'delete'], '/category/del/{id}', ['uses' => 'CategoryController@del_product_category', 'as'=>'categoryDel']);
                        Route::any('/get_product_category_data', 'CategoryController@get_product_category_data');
                        Route::any('/get_editing_category_data/{id}', 'CategoryController@get_editing_category_data');

                        Route::get('/favorite', ['uses'=>'ProductController@favorite', 'as'=>'favorite']);
                    }); 

                    Route::group(['prefix'=>'services', 'namespace'=>'Services'], function() {
                        Route::any('/create_temporary_Service', 'ServiceController@create_temporary_Service');
                        Route::any('/del_temporary_Service/{id}', 'ServiceController@del_temporary_Service');

                        Route::match(['get','post'], '/add_Service', ['uses'=>'ServiceController@add_Service_page','as'=>'serviceAddPage']);
                        Route::match(['get', 'post'], '/edit_Service/{id}', ['uses' => 'ServiceController@edit_Service_page', 'as'=>'serviceEditPage']);

                        Route::match(['get','post'], '/global/add/', ['uses'=>'GlobalServiceController@add_global_Service','as'=>'globalServiceAdd']);
                        Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalServiceController@edit_global_Service', 'as'=>'globalServiceEdit']);

                        Route::match(['get','post'], '/us/add/', ['uses'=>'UsServiceController@add_us_Service','as'=>'usServiceAdd']);
                        Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsServiceController@edit_us_Service', 'as'=>'usServiceEdit']);

                        Route::match(['get','post'], '/ru/add/', ['uses'=>'RuServiceController@add_ru_Service','as'=>'ruServiceAdd']);
                        Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuServiceController@edit_ru_Service', 'as'=>'ruServiceEdit']);

                        Route::match(['get','post'], '/ka/add/', ['uses'=>'KaServiceController@add_ka_Service','as'=>'kaServiceAdd']);
                        Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaServiceController@edit_ka_Service', 'as'=>'kaServiceEdit']);

                        Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ServiceController@del_Service', 'as'=>'ServiceDel']);

                        Route::get('/', ['uses'=>'ServiceController@Service_list', 'as'=>'services_list']);
                        Route::any('/get_service_data', 'ServiceController@get_Service_data');
                        Route::any('/get_Service_editing_data/{editing_Service_id}', 'ServiceController@get_Service_editing_data');
                        Route::any('/get_temporary_Service_editing_data', 'ServiceController@get_temporary_Service_editing_data');
                        Route::any('/get_Service_image/{Service_id}', 'ServiceController@get_Service_image');

                        Route::any('/upload_Service_image/{Service_id}', 'ServiceController@upload_Service_image');
                        Route::any('/del_Service_image/{image_id}', 'ServiceController@del_Service_image');
                        Route::any('/update_Service_image/{id}', 'ServiceController@update_Service_image');

                        Route::get('/favorite', ['uses'=>'ServiceController@favorite', 'as'=>'favorite']);
                    }); 


                    Route::group(['prefix'=>'gallery'], function() {
                        Route::get('/', ['uses'=>'GalleryController@index', 'as'=>'gallery_list']);
                        Route::match(['get','post'], '/add', ['uses'=>'GalleryController@add','as'=>'galleryAdd']);
                        Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'GalleryController@edit', 'as'=>'galleryEdit']);
                        Route::match(['get', 'post', 'delete'], '/delete/{id}', ['uses' => 'GalleryController@delete', 'as'=>'galleryDel']);
                    });
                   
            
                    Route::group(['prefix'=>'options'], function() {
                        Route::get('/', ['uses'=>'UserController@options_index', 'as'=>'options']);
                        Route::match(['get', 'post'], '/user_info_update/{id}', ['uses' => 'UserController@user_info_update', 'as'=>'userInfoUpdate']);
                        Route::patch('/user_image_update/{id}', 'UserController@user_image_update');
                        Route::any('/get_user_data', 'UserController@get_user_data');
                    });


                    Route::group(['prefix'=>'users'], function() {
                        Route::get('/', ['uses'=>'UserController@users_list', 'as'=>'users']);
                        Route::match(['post'], '/del_user/{user_id}', ['uses' => 'UserController@destroy', 'as'=>'userDel']);

                        Route::any('/get_all_users_data', 'UserController@get_all_users_data');
                        Route::any('/get_roles_data', 'UserController@get_roles_data');
                    });


                    Route::group(['prefix'=>'reviews_and_comments'], function() {
                        Route::get('/', ['uses'=>'CommentsController@index', 'as'=>'comments_list']);
                        Route::any('/get_my_comment_data', 'CommentsController@get_my_comments_data');
                        Route::any('/get_all_comment_data', 'CommentsController@get_all_comments_data');
                        Route::match(['post', 'delete'], '/delete_comment/{id}', ['uses' => 'CommentsController@delete', 'as'=>'commentDel']);
                    });


                    Route::group(['prefix'=>'about'], function() {
                        Route::get('/', ['uses'=>'AboutController@index', 'as'=>'about']);
                        Route::match(['get', 'post'], '/site_info_edit_form/{id}', ['uses' => 'AboutController@site_info_edit_form', 'as'=>'siteInfoEditForm']);
                        Route::match(['get', 'post'], '/site_info_edit/{id}', ['uses' => 'AboutController@site_info_edit', 'as'=>'siteInfoEdit']);
                        Route::match(['get', 'post'], '/site_image_edit/{id}', ['uses' => 'AboutController@site_image_edit', 'as'=>'siteImageEdit']);
                        Route::any('/get_site_editing_data/{id}', 'AboutController@get_site_editing_data');
                    });
            });
        });
    });
});

Route::get('/redirect', 'Auth\SocialAuthFacebookController@redirect');
Route::get('/callback', 'Auth\SocialAuthFacebookController@callback');

Auth::routes(['verify' => true]);
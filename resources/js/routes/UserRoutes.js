import homePage from '../components/user/pages/HomePageComponent.vue'
import cartPage from '../components/user/pages/cartPageComponent.vue'
import myOrderPage from '../components/user/pages/myOrderComponent.vue'
import allOrderPage from '../components/user/pages/allOrderPageComponent.vue'
import favoriteProductsPage from '../components/user/pages/favoriteProductsPageComponent.vue'
import galleryPage from '../components/user/pages/GalleryPageComponent.vue'
import optionsPage from '../components/user/pages/optionsPageComponent.vue'
import interestedEventsPage from '../components/user/pages/interestedEventsPageComponent.vue'
import usersList from '../components/user/pages/usersListPageComponent.vue'

import commentsPage from '../components/user/pages/comments/CommentsPageComponent.vue'
import myCommentsPage from '../components/user/pages/comments/MyCommentsPageComponent.vue'

import siteInfoPage from '../components/user/pages/siteInfo/siteInfoPageComponent.vue'
import siteInfoEdit from '../components/user/pages/siteInfo/siteInfoEditComponent.vue'

import filmsList from '../components/user/pages/films/filmsListComponent.vue'
import filmsAdd from '../components/user/pages/films/filmsAddComponent.vue'
import filmsEdit from '../components/user/pages/films/filmsEditComponent.vue'


import mountRouteEdit from '../components/user/pages/mountRoutes/mountRouteEditComponent.vue'
import mountRouteAdd from '../components/user/pages/mountRoutes/mountRouteAddComponent.vue'


import articleList from '../components/user/pages/articles/articleListComponent.vue'
import articleEdit from '../components/user/pages/articles/articleEditComponent.vue'
import articleAdd from '../components/user/pages/articles/articleAddComponent.vue'


import routeAndSectorList from '../components/user/pages/routes_and_sectors/routeAndSectorListComponent.vue'

import routeAdd from '../components/user/pages/routes_and_sectors/routes/routeAddComponent.vue'
import routeEdit from '../components/user/pages/routes_and_sectors/routes/routeEditComponent.vue'

import sectorAdd from '../components/user/pages/routes_and_sectors/sectors/sectorAddComponent.vue'
import sectorEdit from '../components/user/pages/routes_and_sectors/sectors/sectorEditComponent.vue'

import MTPAdd from '../components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue'
import MTPEdit from '../components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue'

import MTPPitchAdd from '../components/user/pages/routes_and_sectors/MTPPitch/MTPPitchAddComponent.vue'
import MTPPitchEdit from '../components/user/pages/routes_and_sectors/MTPPitch/MTPPitchEditComponent.vue'


// import productsList from '../components/user/pages/products_and_categories/products/productsListComponent.vue'
import productsList from '../components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue'
import productEdit from '../components/user/pages/products_and_categories/products/productEditComponent.vue'
import productAdd from '../components/user/pages/products_and_categories/products/productAddComponent.vue'

import productCategoryEdit from '../components/user/pages/products_and_categories/categories/productCategoryEditComponent.vue'
import productCategoryAdd from '../components/user/pages/products_and_categories/categories/productCategoryAddComponent.vue'

import servicesList from '../components/user/pages/services/servicesListComponent.vue'
import serviceEdit from '../components/user/pages/services/serviceEditComponent.vue'
import serviceAdd from '../components/user/pages/services/serviceAddComponent.vue'

import NotFound from '../components/errors/404Component.vue'
export default{
    routes: [
        { path: '/', name: 'home', component: homePage },
        { path: '/cart', name: 'cart', component: cartPage },
        { path: '/my_order', name: 'myOrders', component: myOrderPage },
        { path: '/all_order', name: 'allOrders', component: allOrderPage },
        { path: '/favorite_products', name: 'favoriteProducts', component: favoriteProductsPage },
        { path: '/gallery', name: 'gallery', component: galleryPage },
        { path: '/options', name: 'options', component: optionsPage },
        { path: '/interested_events', name: 'interestedEvents', component: interestedEventsPage },
        { path: '/users_profile_list', name: 'usersList', component: usersList },

        { path: '/comments', name: 'comentsList', component: commentsPage },
        { path: '/myComments', name: 'myComentsList', component: myCommentsPage },

        { path: '/site_info', name: 'siteInfo', component: siteInfoPage },
        { path: '/site_info_edit', name: 'siteInfoEdit', component: siteInfoEdit },

        { path: '/films', name: 'filmsList', component: filmsList },
        { path: '/films/add', name: 'filmsAdd', component: filmsAdd },
        { path: '/films/edit/:id', name: 'filmsEdit', component: filmsEdit },

        { path: '/mount_routes/edit/:id', name: 'mountRoute/Edit', component: mountRouteEdit },
        { path: '/mount_routes/add', name: 'mountRoute/add', component: mountRouteAdd },

        { path: '/article/:article_category', name: 'articlelist', component: articleList },
        { path: '/article/edit/:id', name: 'articleEdit', component: articleEdit },
        { path: '/article/add/', name: 'articleAdd', component: articleAdd },


        { path: '/routes_and_sectors', name: 'routeAndSectorList', component: routeAndSectorList },

        { path: '/route/add', name: 'routeAdd', component: routeAdd },
        { path: '/route/edit/:id', name: 'routeEdit', component: routeEdit },

        { path: '/sector/add', name: 'sectorAdd', component: sectorAdd },
        { path: '/sector/edit/:id', name: 'sectorEdit', component: sectorEdit },

        { path: '/MTP/add', name: 'MTPAdd', component: MTPAdd },
        { path: '/MTP/edit/:id', name: 'MTPEdit', component: MTPEdit },

        { path: '/MTPPitch/add', name: 'MTPPitchAdd', component: MTPPitchAdd },
        { path: '/MTPPitch/edit/:id', name: 'MTPPitchEdit', component: MTPPitchEdit },



        { path: '/products_and_categories', name: 'productsList', component: productsList },
        { path: '/product/edit/:id', name: 'productEdit', component: productEdit },
        { path: '/product/add/', name: 'productAdd', component: productAdd },

        { path: '/productCategory/edit/:id', name: 'productCategoryEdit', component: productCategoryEdit },
        { path: '/productCategory/add/', name: 'productCategoryAdd', component: productCategoryAdd },


        { path: '/services_list', name: 'servicesList', component: servicesList },
        { path: '/service/edit/:id', name: 'serviceEdit', component: serviceEdit },
        { path: '/service/add/', name: 'serviceAdd', component: serviceAdd },


        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
};





// Route::group(['prefix'=>'mountaineering', 'namespace'=>'Mountaineering'], function() {
//     Route::get('/', ['uses'=>'MountListController@index', 'as'=>'mountaineering']);

//     Route::any('/get_mount_data', 'MountListController@get_mount_data');
//     Route::any('/get_mount_route_data', 'MountListController@get_mount_route_data');

//     Route::any('/get_mount_editing_data/{id}', 'MountMassiveController@get_mount_editing_data');

//     Route::match(['get','post'], '/mount_add_form', ['uses'=>'MountMassiveController@add_form','as'=>'mountAddForm']);
//     Route::match(['get','post'], '/mount_add', ['uses'=>'MountMassiveController@add','as'=>'mountAdd']);
//     Route::match(['get', 'post', 'delete'], '/mount_edit_form/{id}', ['uses' => 'MountMassiveController@edit_form', 'as'=>'mountEditForm']);
//     Route::match(['get', 'post', 'delete'], '/mount_edit/{id}', ['uses' => 'MountMassiveController@edit', 'as'=>'mountEdit']);
//     Route::match(['get', 'post', 'delete'], '/mount_delete/{id}', ['uses' => 'MountMassiveController@delete', 'as'=>'mountDel']);
// });

// Route::group(['prefix'=>'routes_and_sectors', 'namespace'=>'Route_And_Sector'], function() {
//     Route::get('/', ['uses'=>'RoutesListController@index', 'as'=>'routes_and_sectors']);
//     Route::match(['post'],'/routesNumEdit', ['uses'=>'RoutesListController@store', 'as'=>'routesNumEdit']);
//     Route::any('/get_region_data', 'RoutesListController@get_region_data');
//     Route::any('/get_route_data', 'RoutesListController@get_route_data');

//     Route::any('/get_routes_for_model/{sector_id}', 'RoutesListController@get_routes_for_model');
//     Route::any('/get_mtp_for_model/{sector_id}', 'RoutesListController@get_mtp_for_model');
//     Route::any('/get_mtp_pitchs_for_model/{mtp_id}', 'RoutesListController@get_mtp_pitchs_for_model');
    
//     Route::match(['get','post'], '/sector_add_form', ['uses'=>'SectorController@add_form','as'=>'sectorAddForm']);
//     Route::match(['get','post'], '/sector_add', ['uses'=>'SectorController@add','as'=>'sectorAdd']);
//     Route::match(['get', 'post', 'delete'], '/sector_edit_form/{id}', ['uses' => 'SectorController@edit_form', 'as'=>'sectorEditForm']);
//     Route::match(['get', 'post', 'delete'], '/sector_edit/{id}', ['uses' => 'SectorController@edit', 'as'=>'sectorEdit']);
//     Route::match(['get', 'post', 'delete'], '/sector_delete/{id}', ['uses' => 'SectorController@delete', 'as'=>'sectorDel']);
//     Route::any( '/save_sector_images_sequence', 'SectorController@save_sector_images_sequence');
    
//     Route::any('/get_sector_editing_data/{id}', 'SectorController@get_sector_editing_data');
//     Route::any('/get_sector_image/{sector_id}', 'SectorController@get_sector_image');
//     Route::any('/get_sector_data', 'RoutesListController@get_sector_data');
//     Route::any('/get_temporary_sector_editing_data', 'SectorController@get_temporary_sector_editing_data');

//     Route::any('/create_temporary_sector', 'SectorController@create_temporary_sector');
//     Route::any('/delete_temporary_sector/{sector_id}', 'SectorController@delete_temporary_sector');

//     Route::match(['get', 'post'], '/upload_sector_image/{sector_id}', ['uses' => 'SectorController@sector_image_upload', 'as'=>'sectorImageUpload']);
//     Route::match(['get', 'post'], '/update_sector_image/{id}', ['uses' => 'SectorController@sector_image_update', 'as'=>'sectorImageUpdate']);
//     Route::match(['get', 'post'], '/delete_sector_image/{id}', ['uses' => 'SectorController@sector_image_delete', 'as'=>'sectorImageDelete']);
    

//     Route::match(['get','post'], '/route_add', ['uses'=>'RoutesController@add','as'=>'routeAdd']);
//     Route::match(['get', 'post', 'delete'], '/route_edit_form/{id}', ['uses' => 'RoutesController@edit_form', 'as'=>'routeEditForm']);
//     Route::match(['get', 'post', 'delete'], '/route_edit/{id}', ['uses' => 'RoutesController@edit', 'as'=>'routeEdit']);
//     Route::match(['get', 'post', 'delete'], '/route_delete/{id}', ['uses' => 'RoutesController@delete', 'as'=>'routeDel']);
//     Route::any('/get_route_editing_data/{id}', 'RoutesController@get_route_editing_data');
//     Route::any('/routes_sequence', 'RoutesController@routes_sequence');

//     Route::match(['get','post'], '/mtp_add', ['uses'=>'MtpController@add_mtp','as'=>'mtpAdd']);
//     Route::match(['get', 'post', 'delete'], '/mtp_edit_form/{id}', ['uses' => 'MtpController@edit_mtp_form', 'as'=>'mtpEditForm']);
//     Route::match(['get', 'post', 'delete'], '/mtp_edit/{id}', ['uses' => 'MtpController@edit_mtp', 'as'=>'mtpEdit']);
//     Route::match(['get', 'post', 'delete'], '/mtp_delete/{id}', ['uses' => 'MtpController@delete_mtp', 'as'=>'mtpDelete']);
//     Route::any('/get_mtp_editing_data/{id}', 'MtpController@get_mtp_editing_data');
//     Route::any('/get_mtp_data', 'RoutesListController@get_mtp_data');

//     Route::match(['get','post'], '/mtp_pitch_add', ['uses'=>'MtpController@add_pitch','as'=>'mtpPitchAdd']);
//     Route::match(['get', 'post', 'delete'], '/mtp_pitch_edit_form/{id}', ['uses' => 'MtpController@edit_pitch_form', 'as'=>'mtpPitchEditForm']);
//     Route::match(['get', 'post', 'delete'], '/mtp_pitch_edit/{id}', ['uses' => 'MtpController@edit_pitch', 'as'=>'mtpPitchEdit']);
//     Route::match(['get', 'post', 'delete'], '/mtp_pitch_delete/{id}', ['uses' => 'MtpController@delete_pitch', 'as'=>'mtpPitchDel']);
//     Route::any('/get_mtp_pitch_editing_data/{id}', 'MtpController@get_mtp_pitch_editing_data');
//     Route::any('/get_mtp_pitch_data', 'RoutesListController@get_mtp_pitch_data');
//     Route::any('/get_mtp_pitch_for_modal/{mtp_id}', 'MtpController@get_mtp_pitch_for_modal');
//     Route::any('/pitchs_sequence', 'MtpController@pitchs_sequence');
// }); 


// Route::group(['prefix'=>'/articles', 'namespace'=>'Article'], function() {
//     Route::any('/create_temporary_article/{category}', 'ArticleController@create_temporary_article');
//     Route::any('/del_temporary_article/{id}', 'ArticleController@delete');
//     Route::any('/get_temporary_article_editing_data', 'ArticleController@get_temporary_article_editing_data');

//     Route::match(['get','post'], '/global/add/{category}', ['uses'=>'GlobalArticleController@add_global_article','as'=>'globalArticleAdd']);
//     Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalArticleController@edit_global_article', 'as'=>'globalArticleEdit']);

//     Route::match(['get','post'], '/us/add/{category}', ['uses'=>'UsArticleController@add_us_article','as'=>'usArticleAdd']);
//     Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsArticleController@edit_us_article', 'as'=>'usArticleEdit']);

//     Route::match(['get','post'], '/ru/add/{category}', ['uses'=>'RuArticleController@add_ru_article','as'=>'ruArticleAdd']);
//     Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuArticleController@edit_ru_article', 'as'=>'ruArticleEdit']);

//     Route::match(['get','post'], '/ka/add/{category}', ['uses'=>'KaArticleController@add_ka_article','as'=>'kaArticleAdd']);
//     Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaArticleController@edit_ka_article', 'as'=>'kaArticleEdit']);

//     Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ArticleController@delete', 'as'=>'articleDel']);



//     Route::get('/{article_category}', ['uses'=>'ArticleController@index', 'as'=>'article_list']);

//     Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'ArticleController@edit_article_page', 'as'=>'articleEdit']);
//     Route::match(['get', 'post'], '/add/{category}', ['uses' => 'ArticleController@add_article_page', 'as'=>'articleAdd']);

//     Route::match(['get', 'post', 'HEAD'], '/upload_image', ['uses' => 'GlobalArticleController@image_upload', 'as'=>'articleImageUpload']);
//     Route::match(['get', 'post', 'HEAD'], '/update_image/{id}', ['uses' => 'GlobalArticleController@image_update', 'as'=>'articleImageUpdate']);

//     Route::match(['post',], '/region_sectors_image_upload', ['uses' => 'GlobalArticleController@region_sectors_image_upload', 'as'=>'region_sectors_image_upload']);
//     Route::match(['post',], '/region_sectors_image_update/{id}', ['uses' => 'GlobalArticleController@region_sectors_image_update', 'as'=>'region_sectors_image_update']);
    
//     Route::match(['post',], '/mount_route_image_upload', ['uses' => 'GlobalArticleController@mount_route_image_upload', 'as'=>'mount_route_image_upload']);
//     Route::match(['post',], '/mount_route_image_update/{id}', ['uses' => 'GlobalArticleController@mount_route_image_update', 'as'=>'mount_route_image_update']);

//     // Route::any('/region_sectors_image_upload', 'GlobalArticleController@region_sectors_image_upload');
//     // Route::any('/region_sectors_image_update/{id}', 'GlobalArticleController@region_sectors_image_update');

//     Route::any('/get_article_data/{article_category}', 'ArticleController@get_article_data');
//     Route::any('/get_editing_data/{id}', 'ArticleController@get_editing_data');
// });    


// Route::group(['prefix'=>'cart'], function() {
//     Route::get('/my_cart', ['uses'=>'CartController@index', 'as'=>'cart_index']);

//     Route::any('/get_products_cart', 'CartController@get_products_cart');
//     Route::any('/add_to_cart/{id}', 'CartController@add_to_cart');
//     Route::any('/update_quantity/{id}', 'CartController@update_quantity');
//     Route::any('/del_item/{id}', 'CartController@del_item');
// }); 

// Route::group(['prefix'=>'order'], function() {
//     Route::get('/my_orders', ['uses'=>'OrdersController@user_orders', 'as'=>'my_orders']);
//     Route::get('/', ['uses'=>'OrdersController@orders_list', 'as'=>'all_orders']);

//     Route::any('/get_all_orders', 'OrdersController@get_all_orders');
//     Route::any('/get_user_orders', 'OrdersController@get_user_orders');
//     Route::any('/get_orders_data/{id}', 'OrdersController@11');

//     Route::any('/arrange_order', 'OrdersController@arrange_order');
//     Route::any('/cont_shopping', 'OrdersController@cont_shopping');
// }); 


// Route::group(['prefix'=>'products', 'namespace'=>'Product'], function() {
//     Route::any('/create_temporary_product', 'ProductController@create_temporary_product');
//     Route::any('/del_temporary_product/{id}', 'ProductController@del_temporary_product');

//     Route::match(['get','post'], '/add_product', ['uses'=>'ProductController@add_product_page','as'=>'productAddPage']);
//     Route::match(['get', 'post'], '/edit_product/{id}', ['uses' => 'ProductController@edit_product_page', 'as'=>'productEditPage']);

//     Route::match(['get','post'], '/global/add/', ['uses'=>'GlobalProductController@add_global_product','as'=>'globalProductAdd']);
//     Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalProductController@edit_global_product', 'as'=>'globalProductEdit']);

//     Route::match(['get','post'], '/us/add/', ['uses'=>'UsProductController@add_us_product','as'=>'usProductAdd']);
//     Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsProductController@edit_us_product', 'as'=>'usProductEdit']);

//     Route::match(['get','post'], '/ru/add/', ['uses'=>'RuProductController@add_ru_product','as'=>'ruProductAdd']);
//     Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuProductController@edit_ru_product', 'as'=>'ruProductEdit']);

//     Route::match(['get','post'], '/ka/add/', ['uses'=>'KaProductController@add_ka_product','as'=>'kaProductAdd']);
//     Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaProductController@edit_ka_product', 'as'=>'kaProductEdit']);

//     Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ProductController@del_product', 'as'=>'productDel']);

//     Route::get('/', ['uses'=>'ProductController@product_list_page', 'as'=>'products_list']);
//     Route::any('/get_product_data', 'ProductController@get_product_data');
//     Route::any('/get_product_editing_data/{editing_product_id}', 'ProductController@get_product_editing_data');
//     Route::any('/get_temporary_product_editing_data', 'ProductController@get_temporary_product_editing_data');
//     Route::any('/get_product_image/{product_id}', 'ProductController@get_product_image');

//     Route::any('/upload_product_image/{product_id}', 'ProductController@upload_product_image');
//     Route::any('/del_product_image/{image_id}', 'ProductController@del_product_image');
//     Route::any('/update_product_image/{id}', 'ProductController@update_product_image');

//     Route::match(['get','post'], '/category/add/form/', ['uses'=>'CategoryController@product_add_form','as'=>'categoryAddForm']);
//     Route::match(['get','post'], '/category/add/', ['uses'=>'CategoryController@add_product_category','as'=>'categoryAdd']);
//     Route::match(['get', 'post'], '/category/edit/form/{id}', ['uses' => 'CategoryController@product_edit_form', 'as'=>'categoryEditForm']);
//     Route::match(['get', 'post'], '/category/edit/{id}', ['uses' => 'CategoryController@edit_product_category', 'as'=>'categoryEdit']);
//     Route::match(['post', 'delete'], '/category/del/{id}', ['uses' => 'CategoryController@del_product_category', 'as'=>'categoryDel']);
//     Route::any('/get_product_category_data', 'CategoryController@get_product_category_data');
//     Route::any('/get_editing_category_data/{id}', 'CategoryController@get_editing_category_data');

//     Route::get('/favorite', ['uses'=>'ProductController@favorite', 'as'=>'favorite']);
// }); 

// Route::group(['prefix'=>'services', 'namespace'=>'Services'], function() {
//     Route::any('/create_temporary_service', 'ServiceController@create_temporary_service');
//     Route::any('/del_temporary_service/{id}', 'ServiceController@del_temporary_service');

//     Route::match(['get','post'], '/add_service', ['uses'=>'ServiceController@add_service_page','as'=>'serviceAddPage']);
//     Route::match(['get', 'post'], '/edit_service/{id}', ['uses' => 'ServiceController@edit_service_page', 'as'=>'serviceEditPage']);

//     Route::match(['get','post'], '/global/add/', ['uses'=>'GlobalServiceController@add_global_service','as'=>'globalServiceAdd']);
//     Route::match(['get', 'post'], '/global/edit/{id}', ['uses' => 'GlobalServiceController@edit_global_service', 'as'=>'globalServiceEdit']);

//     Route::match(['get','post'], '/us/add/', ['uses'=>'UsServiceController@add_us_service','as'=>'usServiceAdd']);
//     Route::match(['get', 'post'], '/us/edit/{id}', ['uses' => 'UsServiceController@edit_us_service', 'as'=>'usServiceEdit']);

//     Route::match(['get','post'], '/ru/add/', ['uses'=>'RuServiceController@add_ru_service','as'=>'ruServiceAdd']);
//     Route::match(['get', 'post'], '/ru/edit/{id}', ['uses' => 'RuServiceController@edit_ru_service', 'as'=>'ruServiceEdit']);

//     Route::match(['get','post'], '/ka/add/', ['uses'=>'KaServiceController@add_ka_service','as'=>'kaServiceAdd']);
//     Route::match(['get', 'post'], '/ka/edit/{id}', ['uses' => 'KaServiceController@edit_ka_service', 'as'=>'kaServiceEdit']);

//     Route::match(['post', 'delete'], '/del/{id}', ['uses' => 'ServiceController@del_service', 'as'=>'ServiceDel']);

//     Route::get('/', ['uses'=>'ServiceController@service_list', 'as'=>'services_list']);
//     Route::any('/get_service_data', 'ServiceController@get_service_data');
//     Route::any('/get_service_editing_data/{editing_service_id}', 'ServiceController@get_service_editing_data');
//     Route::any('/get_temporary_service_editing_data', 'ServiceController@get_temporary_service_editing_data');
//     Route::any('/get_service_image/{service_id}', 'ServiceController@get_service_image');

//     Route::any('/upload_service_image/{service_id}', 'ServiceController@upload_service_image');
//     Route::any('/del_service_image/{image_id}', 'ServiceController@del_service_image');
//     Route::any('/update_service_image/{id}', 'ServiceController@update_service_image');
// }); 


// Route::group(['prefix'=>'gallery'], function() {
//     Route::get('/', ['uses'=>'GalleryController@index', 'as'=>'gallery_list']);

//     Route::any('/get_gallery_data', 'GalleryController@get_gallery_data');
//     Route::any('/get_articles', 'GalleryController@get_articles');
//     Route::any('/get_image/{id}', 'GalleryController@get_image');
//     Route::any('/get_editing_image/{id}', 'GalleryController@get_editing_image');

//     Route::match(['get','post'], '/add', ['uses'=>'GalleryController@add','as'=>'galleryAdd']);
//     Route::match(['get', 'post'], '/edit/{id}', ['uses' => 'GalleryController@edit', 'as'=>'galleryEdit']);

//     Route::match(['get', 'post', 'delete'], '/delete/{id}', ['uses' => 'GalleryController@delete', 'as'=>'galleryDel']);
// });


// Route::group(['prefix'=>'options'], function() {
//     Route::get('/', ['uses'=>'UserController@options_index', 'as'=>'options']);
//     Route::match(['get', 'post'], '/user_info_update/{id}', ['uses' => 'UserController@user_info_update', 'as'=>'userInfoUpdate']);
//     Route::patch('/user_image_update/{id}', 'UserController@user_image_update');
//     Route::any('/get_user_data', 'UserController@get_user_data');
// });


// Route::group(['prefix'=>'users'], function() {
//     Route::get('/', ['uses'=>'UserController@users_list', 'as'=>'users']);
//     Route::match(['post'], '/del_user/{user_id}', ['uses' => 'UserController@destroy', 'as'=>'userDel']);

//     Route::any('/edit_user_permission/{user_id}', 'UserController@edit_user_permission');
//     Route::any('/get_role', 'UserController@get_role');

//     Route::any('/get_all_users_data', 'UserController@get_all_users_data');
//     Route::any('/get_roles_data', 'UserController@get_roles_data');

//     Route::any('/get_user_parmisions', 'UserController@get_user_parmisions');
// });


// Route::group(['prefix'=>'reviews_and_comments'], function() {
//     Route::get('/', ['uses'=>'CommentsController@index', 'as'=>'comments_list']);
//     Route::any('/get_my_comment_data', 'CommentsController@get_my_comments_data');
//     Route::any('/get_all_comment_data', 'CommentsController@get_all_comments_data');
//     Route::match(['post', 'delete'], '/delete_comment/{id}', ['uses' => 'CommentsController@delete', 'as'=>'commentDel']);
// });


// Route::group(['prefix'=>'about'], function() {
//     Route::get('/', ['uses'=>'AboutController@index', 'as'=>'about']);
//     Route::any('/get_site_data', 'AboutController@get_site_data');
//     Route::match(['get', 'post'], '/site_info_edit_form/{id}', ['uses' => 'AboutController@site_info_edit_form', 'as'=>'siteInfoEditForm']);
//     Route::match(['get', 'post'], '/site_info_edit/{id}', ['uses' => 'AboutController@site_info_edit', 'as'=>'siteInfoEdit']);
//     Route::match(['get', 'post'], '/site_image_edit/{id}', ['uses' => 'AboutController@site_image_edit', 'as'=>'siteImageEdit']);
//     Route::any('/get_site_editing_data/{id}', 'AboutController@get_site_editing_data');
// });


// Route::group(['prefix'=>'events'], function() {
//     Route::get('/interested', ['uses'=>'InteestedEventCntroller@interested', 'as'=>'interested']);
// });
// });
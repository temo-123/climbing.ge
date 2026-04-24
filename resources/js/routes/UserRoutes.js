import { createRouter, createWebHistory, createMemoryHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

import NotFound from '../components/errors/404Component.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

import forget_pass from '../components/auth/reset_password/ForgetPasswordComponent.vue'
import reset_pass from '../components/auth/reset_password/ResetPasswordComponent.vue'
import create_password from '../components/auth/social/CreatePasswordComponent.vue'
import callback_password from '../components/auth/social/CallbackComponent.vue'
import verify from '../components/auth/Verify.vue'

function load(component) {
    return () => import(`../components/user/pages/${component}.vue`)
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}
const routes = [
{
        path: `/:locale${getLocaleRegex()}?`,
        component: {
            render() {
                return h(RouterView);
            }
        },
        children: [
{ path: '', name: 'home', component: load('HomePageComponent'), meta: { title: 'Home' } },

            { path: 'shop_task_panel', name: 'shopTaskPanel', component: load('dashboards/shop/ShopTaskPanelComponent'),meta: {title: 'user page'}},
            { path: 'shop_dashboard_page', name: 'shopDashboardPage', component: load('dashboards/shop/ShopDashboardPageComponent'), meta: {title: 'user page'}},
            { path: 'guide_task_panel', name: 'guideTaskPanel', component: load('dashboards/guide/GuideTaskPanelComponent'),meta: {title: 'user page'}},
            { path: 'guide_dashboard_page', name: 'guideDashboardPage', component: load('dashboards/guide/GuideDashboardPageComponent'), meta: {title: 'user page'}},

            { path: 'purchases', name: 'userPurchases', component: load('orders/PurchasesPage'), meta: {title: 'user page'}},
            { path: 'order/user_orders', name: 'userOrders', component: load('orders/PurchasesPage'), meta: {title: 'user page'}},
            { path: 'order/all_orders', name: 'allOrders', component: load('orders/allOrderPageComponent'), meta: {title: 'user page'}},

            { path: 'order/decloration/order_decloration/:payment/:adres/:discount?', name: 'orderDecloration', component: load('orders/decloration/orderDeclorationPageComponent'), meta: {title: 'user page'}},
            { path: 'order/decloration/order_payment', name: 'orderPayment', component: load('orders/decloration/orderPaymentPageComponent'), meta: {title: 'user page'}},
            { path: 'order/decloration/confirm_order/:order_decloration?', name: 'confirmOrder', component: load('orders/decloration/confirmOrderPageComponent'), meta: {title: 'user page'}},
                        
            { path: 'order/confirm_order/:order_id/user/:user_id', name: 'mailConfirmOrder', component: load('orders/decloration/mailOrderConfirmComponent'), meta: {title: 'user page'}},

            { path: 'posts', name: 'posts', component: load('posts/PostsListComponent'), meta: {title: 'user page'}},
            { path: 'post/add', name: 'add_post', component: load('posts/AddPostFormComponent'), meta: {title: 'user page'}},
            { path: 'post/edit/:id', name: 'edit_post', component: load('posts/EditPostFormComponent'), meta: {title: 'user page'}},

            { path: 'cart', name: 'cart', component: load('cartPageComponent'), meta: {title: 'user page'}},
            { path: 'head_sliders_images', name: 'head_sliders_images', component: load('gallery/HeadSlidersPage'), meta: {title: 'user page'}},
            { path: 'site_folloers', name: 'site_folloers', component: load('SiteFolloersListPageComponent'), meta: {title: 'user page'}},
            { path: 'options', name: 'options', component: load('optionsPageComponent'), meta: {title: 'user page'}},
            { path: 'users_list', name: 'usersList', component: load('usersListPageComponent'), meta: {title: 'user page'}},

            { path: 'comments_and_reviews', name: 'commentsAndReviewsList', component: load('comments_&_reviews/all_actions/comments_&_reviews_page'), meta: {title: 'user page'}},

            { path: 'my_comments_and_reviews', name: 'myCommentsAndReviewsList', component: load('comments_&_reviews/my_actions/my_comments_&_reviews_page'), meta: {title: 'user page'}},

            { path: 'favorite_films', name: 'favoriteFilmsPage', component: load('favorites/FavoriteFilmsPageComponent'), meta: {title: 'user page'}},
            { path: 'my_favorite_outdoor_area', name: 'favoriteOutdoorArea', component: load('favorites/FavoriteOutdoorAreaPageComponent'), meta: {title: 'user page'}},
            { path: 'interested_events', name: 'interestedEvents', component: load('favorites/InterestedEventsPageComponent'), meta: {title: 'user page'}},
            { path: 'favorite_products', name: 'favoriteProducts', component: load('favorites/FavoriteProductsPageComponent'), meta: {title: 'user page'}},

            { path: 'site_info', name: 'siteInfo', component: load('siteInfo/siteInfoPageComponent'), meta: {title: 'user page'}},

            { path: 'site_info/site_data_edit', name: 'siteDataEdit', component: load('siteInfo/SiteData/SiteInfoEditComponent'), meta: {title: 'user page'}},
            { path: 'site_info/site_global_data_edit', name: 'siteGlobalDataEdit', component: load('siteInfo/SiteData/SiteGlobalInfoEditComponent'), meta: {title: 'user page'}},
            { path: 'site_info/site_global_ka_edit', name: 'siteKaDataEdit', component: load('siteInfo/SiteData/SiteKaInfoEditComponent'), meta: {title: 'user page'}},
            { path: 'site_info/site_global_ru_edit', name: 'siteRuDataEdit', component: load('siteInfo/SiteData/SiteRuInfoEditComponent'), meta: {title: 'user page'}},
            { path: 'site_info/site_global_us_edit', name: 'siteUsDataEdit', component: load('siteInfo/SiteData/SiteUsInfoEditComponent'), meta: {title: 'user page'}},

            { path: 'site_info/add_general_info', name: 'GlobalInfoAdd', component: load('siteInfo/GeneralInfo/GlobalInfoAddFormComponent'), meta: {title: 'user page'}},
            { path: 'site_info/edit_general_info/:id', name: 'GlobalInfoEdit', component: load('siteInfo/GeneralInfo/GlobalInfoEditFormComponent'), meta: {title: 'user page'}},

            { path: 'films', name: 'filmsList', component: load('films/filmsListComponent'), meta: {title: 'user page'}},
            { path: 'films/add', name: 'filmsAdd', component: load('films/filmsAddComponent'), meta: {title: 'user page'}},
            { path: 'films/edit/:id', name: 'filmsEdit', component: load('films/filmsEditComponent'), meta: {title: 'user page'}},

            { path: 'mount_massive/edit/:id', name: 'mount_massive_edit', component: load('mountRoutes/mountMassiveEditComponent'), meta: {title: 'user page'}},
            { path: 'mount_massive/add/', name: 'mount_massive_add', component: load('mountRoutes/mountMassiveAddComponent'), meta: {title: 'user page'}},

            { path: 'article/:article_category', name: 'articlelist', component: load('articles/articleListComponent'), meta: {title: 'user page'}},
            { path: 'article/edit/:id', name: 'articleEdit', component: load('articles/articleEditComponent'), meta: {title: 'user page'}},
            { path: 'article/add/:article_category', name: 'articleAdd', component: load('articles/articleAddComponent'), meta: {title: 'user page'}},
        
            { path: 'region/edit/:id', name: 'region_edit', component: load('regions/RegionEditPageComponent'), meta: {title: 'user page'}},
            { path: 'region/add/', name: 'region_add', component: load('regions/RegionAddPageComponent'), meta: {title: 'user page'}},

            { path: 'ice_route/add', name: 'iceRouteAdd', component: load('ice/routes/add_route'), meta: {title: 'user page'}},
            { path: 'ice_route/edit/:id', name: 'iceRouteEdit', component: load('ice/routes/edit_route'), meta: {title: 'user page'}},

            { path: 'ice_sector/add', name: 'iceSectorAdd', component: load('ice/sectors/add_sector'), meta: {title: 'user page'}},
            { path: 'ice_sector/edit/:id', name: 'iceSectorEdit', component: load('ice/sectors/edit_sector'), meta: {title: 'user page'}},

            { path: 'routes_and_sectors', name: 'routeAndSectorList', component: load('routes_and_sectors/routeAndSectorListComponent'), meta: {title: 'user page'}},

            { path: 'route/add/:category', name: 'routeAdd', component: load('routes_and_sectors/routes/routeAddComponent'), meta: {title: 'user page'}},
            { path: 'route/edit/:id', name: 'routeEdit', component: load('routes_and_sectors/routes/routeEditComponent'), meta: {title: 'user page'}},
            { path: 'sector/add/:category', name: 'sectorAdd', component: load('routes_and_sectors/sectors/sectorAddComponent'), meta: {title: 'user page'}},
{ path: 'sector/edit/:id', name: 'sectorEdit', component: load('routes_and_sectors/sectors/sectorEditComponent'), props: true, meta: {title: 'user page'}},

            { path: 'MTP/add', name: 'MTPAdd', component: load('routes_and_sectors/MTP/MTPAddComponent'), meta: {title: 'user page'}},
            { path: 'MTP/edit/:id', name: 'MTPEdit', component: load('routes_and_sectors/MTP/MTPEditComponent'), meta: {title: 'user page'}},

            { path: 'sector_local_images', name: 'sectorLocalImagesList', component: load('sector_local_images/sectorLocalImagesListComponent'), meta: {title: 'user page'}},
            { path: 'sector_local_images/add', name: 'sectorLocalImagesListAdd', component: load('sector_local_images/sectorLocalImageAddComponent'), meta: {title: 'user page'}},
            { path: 'sector_local_images/edit/:id', name: 'sectorLocalImagesListEdit', component: load('sector_local_images/sectorLocalImageEditComponent'), meta: {title: 'user page'}},
            { path: 'sector_local_images/options/:id', name: 'sectorLocalImagesOptions', component: load('sector_local_images/sectorLocaleImageEditorComponent'), meta: {title: 'user page'}},

            { path: 'local_bisnes', name: 'localBisnesList', component: load('local_bisnes/localBisnesListComponent'), meta: {title: 'user page'}},
            { path: 'local_bisnes/add', name: 'localBisnesAdd', component: load('local_bisnes/localBisnesAddComponent'), meta: {title: 'user page'}},
            { path: 'local_bisnes/edit/:id', name: 'localBisnesEdit', component: load('local_bisnes/localBisnesEditComponent'), meta: {title: 'user page'}},
            
            { path: 'warehouses', name: 'warehousesList', component: load('warehouses/WarehousesListComponent'), meta: {title: 'user page'}},
            { path: 'warehouse/options/:id', name: 'warehouseOptions', component: load('warehouses/warehouseOptionsComponent'), meta: {title: 'user page'}},

            { path: 'live_cameras', name: 'liveCamerasList', component: load('live_camera/LiveCamerasListComponent'), meta: {title: 'user page'}},

            { path: 'export_tips', name: 'exportTips', component: load('expoet/exportComponent'), meta: {title: 'user page'}},

            { path: 'event', name: 'eventList', component: load('event/eventsAndCompetitionsListComponent'), meta: {title: 'user page'}},
            { path: 'event/add', name: 'eventAdd', component: load('event/events/eventAddComponent'), meta: {title: 'user page'}},
            { path: 'event/edit/:id', name: 'eventEdit', component: load('event/events/eventEditComponent'), meta: {title: 'user page'}},
            
            { path: 'competition/add', name: 'competitionAdd', component: load('event/competitions/competitionAddComponent'), meta: {title: 'user page'}},
            { path: 'competition/edit/:id', name: 'competitionEdit', component: load('event/competitions/competitionEditComponent'), meta: {title: 'user page'}},
            
            { path: 'products_and_categories', name: 'productsList', component: load('products_and_categories/productsAndCatgoriesListComponent'), meta: {title: 'user page'}},
            
            { path: 'product/edit/:id', name: 'productEdit', component: load('products_and_categories/products/productEditComponent'), meta: {title: 'user page'}},
            { path: 'product/add', name: 'productAdd', component: load('products_and_categories/products/productAddComponent'), meta: {title: 'user page'}},

            { path: 'product_brands', name: 'product_brands', component: load('products_and_categories/brands/product_brands'), meta: {title: 'user page'}},
            { path: 'product_option_control/:id', name: 'productOptionsControl', component: load('products_and_categories/product_options/productOptionControlPageComponent'), meta: {title: 'user page'}},

            { path: 'productCategory/edit/:id', name: 'productCategoryEdit', component: load('products_and_categories/categories/productCategoryEditComponent'), meta: {title: 'user page'}},
            { path: 'productCategory/add/', name: 'productCategoryAdd', component: load('products_and_categories/categories/productCategoryAddComponent'), meta: {title: 'user page'}},

            { path: 'user_tours_reservations_list', name: 'userToursReservations', component: load('tour/list/for_user/UserToursReservationsPage'), meta: {title: 'user page'}},
            { path: 'user_tours_list', name: 'userToursList', component: load('tour/list/for_user/UserToursListPage'), meta: {title: 'user page'}},

            { path: 'all_tours_list', name: 'allToursList', component: load('tour/list/for_admin/AllToursListPage'), meta: {title: 'user page'}},
            { path: 'all_tours_reservations_list', name: 'allToursReservations', component: load('tour/list/for_admin/AllToursReservationsPage'), meta: {title: 'user page'}},

            { path: 'tour/edit/:id', name: 'tourEdit', component: load('tour/forms/EditTourPage'), meta: {title: 'user page'}},
            { path: 'tour/add', name: 'tourAdd', component: load('tour/forms/AddTourPage'), meta: {title: 'user page'}},

            { path: 'stor_slides_list', name: 'storSlidesList', component: load('gallery/StoreSlidesPage'), meta: {title: 'user page'}},

            { path: 'non_registered_commenter_list', name: 'nonRegisteredCommenterList', component: load('comments_&_reviews/NonRegisteredCommenterListPage'), meta: {title: 'user page'}},

            { path: 'services_list', name: 'servicesList', component: load('services/servicesListComponent'), meta: {title: 'user page'}},
            { path: 'service/edit/:id', name: 'serviceEdit', component: load('services/serviceEditComponent'), meta: {title: 'user page'}},
            { path: 'service/add/', name: 'serviceAdd', component: load('services/serviceAddComponent'), meta: {title: 'user page'}},

            { path: 'article_images', name: 'article_images', component: load('multimedia/ArticleImagesPage'), meta: {title: 'user page'}},

            { path: 'summits', name: 'summitList', component: load('summits/SummitListPage'), meta: {title: 'user page'}},

            { path: 'login', name: 'login', component: login, meta: {title: 'user page'}},
            { path: 'register', name: 'register', component: register ,meta: {title: 'user page'}},
            { path: 'forget_pass', name: 'forget_pass', component: forget_pass ,meta: {title: 'user page'}},
            { path: 'reset-password/:token/:user_id', name: 'reset_pass', component: reset_pass ,meta: {title: 'user page'}},
            { path: 'create_password/:email', name: 'create_pass', component: create_password ,meta: {title: 'user page'}},
            { path: 'login/:provaider/callback', name: 'callback', component: callback_password ,meta: {title: 'user page'}},
            { path: 'email/verify/:user_id/:hash', name: 'verify', component: verify ,meta: {title: 'user page'}},

            { path: "*", name: 'NotFound', component: NotFound ,meta: {title: 'user page'}},

        ],
    },
];

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

const publicRoutes = ['login', 'register', 'forget_pass', 'reset_pass', 'callback', 'verify', 'create_pass'];

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('x_xsrf_token');

    if (!token) {
        if (publicRoutes.includes(to.name)) return next();
        return next({ name: 'login', query: { redirect: to.fullPath } });
    }

    // Logged in — redirect away from auth pages
    if (publicRoutes.includes(to.name)) {
        return next({ name: 'home' });
    }

    next();
});

export default router;

import { createRouter, createWebHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

import NotFound from '../components/errors/404Component.vue'
import BannedPage from '../components/user/pages/BannedPageComponent.vue'

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

            { path: 'shop_task_panel', name: 'shopTaskPanel', component: load('dashboards/shop/ShopTaskPanelComponent'), meta: {title: 'user page', permissions: [['show', 'shop_task']]}},
            { path: 'shop_dashboard_page', name: 'shopDashboardPage', component: load('dashboards/shop/ShopDashboardPageComponent'), meta: {title: 'user page', permissions: [['show', 'shop_task']]}},
            { path: 'shop_worker_tasks_page', name: 'shopWorkerTasksPage', component: load('dashboards/shop/WorkerTaskPanelPageComponent'), meta: {title: 'user page', permissions: [['show', 'shop_task']]}},

            { path: 'guide_task_panel', name: 'guideTaskPanel', component: load('dashboards/guide/GuideTaskPanelComponent'), meta: {title: 'user page', permissions: [['show', 'guide_task']]}},
            { path: 'guide_dashboard_page', name: 'guideDashboardPage', component: load('dashboards/guide/GuideDashboardPageComponent'), meta: {title: 'user page', permissions: [['show', 'guide_task']]}},
            { path: 'team_member_tasks_page', name: 'teamMemberTasksPage', component: load('dashboards/guide/TeamMembersTaskPanelPageComponent'), meta: {title: 'user page', permissions: [['show', 'team']]}},

            { path: 'purchases', name: 'userPurchases', component: load('orders/PurchasesPage'), meta: {title: 'user page'}},
            { path: 'order/user_orders', name: 'userOrders', component: load('orders/PurchasesPage'), meta: {title: 'user page'}},
            { path: 'order/all_orders', name: 'allOrders', component: load('orders/allOrderPageComponent'), meta: {title: 'user page', permissions: [['show', 'order']]}},

            { path: 'order/decloration/order_decloration/:payment/:adres/:discount?', name: 'orderDecloration', component: load('orders/decloration/orderDeclorationPageComponent'), meta: {title: 'user page'}},
            { path: 'order/decloration/order_payment', name: 'orderPayment', component: load('orders/decloration/orderPaymentPageComponent'), meta: {title: 'user page'}},
            { path: 'order/decloration/confirm_order/:order_decloration?', name: 'confirmOrder', component: load('orders/decloration/confirmOrderPageComponent'), meta: {title: 'user page'}},
            { path: 'payment/success', name: 'paymentSuccess', component: load('orders/PaymentSuccessPage'), meta: {title: 'Payment Success'}},
            { path: 'payment/cancel',  name: 'paymentCancel',  component: load('orders/PaymentCancelPage'),  meta: {title: 'Payment Cancelled'}},

            { path: 'order/confirm_order/:order_id/user/:user_id/token/:token', name: 'mailConfirmOrder', component: load('orders/decloration/mailOrderConfirmComponent'), meta: {title: 'user page'}},

            { path: 'posts', name: 'posts', component: load('posts/PostsListComponent'), meta: {title: 'user page', permissions: [['show', 'post']]}},
            { path: 'post/add', name: 'add_post', component: load('posts/AddPostFormComponent'), meta: {title: 'user page', permissions: [['show', 'post']]}},
            { path: 'post/edit/:id', name: 'edit_post', component: load('posts/EditPostFormComponent'), meta: {title: 'user page', permissions: [['show', 'post']]}},

            { path: 'cart', name: 'cart', component: load('cartPageComponent'), meta: {title: 'user page'}},
            { path: 'head_sliders_images', name: 'head_sliders_images', component: load('gallery/HeadSlidersPage'), meta: {title: 'user page', permissions: [['show', 'head_slider']]}},
            { path: 'options', name: 'options', component: load('optionsPageComponent'), meta: {title: 'user page'}},
            { path: 'users_list', name: 'usersList', component: load('usersListPageComponent'), meta: {title: 'user page', permissions: [['show', 'user']]}},

            { path: 'comments_and_reviews', name: 'commentsAndReviewsList', component: load('comments_&_reviews/all_actions/comments_&_reviews_page'), meta: {title: 'user page', permissions: [['show', 'comment']]}},
            { path: 'all_routes_reviews', name: 'allRoutesReviews', component: load('comments_&_reviews/all_actions/all_routes_reviews_page'), meta: {title: 'user page', permissions: [['show', 'comment']]}},

            { path: 'my_comments_and_reviews', name: 'myCommentsAndReviewsList', component: load('comments_&_reviews/my_actions/my_comments_&_reviews_page'), meta: {title: 'user page'}},
            { path: 'my_routes_review', name: 'myRoutesReview', component: load('comments_&_reviews/my_actions/my_routes_review_page'), meta: {title: 'user page'}},

            { path: 'favorite_films', name: 'favoriteFilmsPage', component: load('favorites/FavoriteFilmsPageComponent'), meta: {title: 'user page'}},
            { path: 'my_favorite_outdoor_area', name: 'favoriteOutdoorArea', component: load('favorites/FavoriteOutdoorAreaPageComponent'), meta: {title: 'user page'}},
            { path: 'interested_events', name: 'interestedEvents', component: load('favorites/InterestedEventsPageComponent'), meta: {title: 'user page'}},
            { path: 'favorite_products', name: 'favoriteProducts', component: load('favorites/FavoriteProductsPageComponent'), meta: {title: 'user page'}},

            { path: 'site_info', name: 'siteInfo', component: load('siteInfo/siteInfoPageComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},

            { path: 'site_info/site_data_edit', name: 'siteDataEdit', component: load('siteInfo/SiteData/SiteInfoEditComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_data_edit', name: 'siteGlobalDataEdit', component: load('siteInfo/SiteData/SiteGlobalInfoEditComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_ka_edit', name: 'siteKaDataEdit', component: load('siteInfo/SiteData/SiteKaInfoEditComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_ru_edit', name: 'siteRuDataEdit', component: load('siteInfo/SiteData/SiteRuInfoEditComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_us_edit', name: 'siteUsDataEdit', component: load('siteInfo/SiteData/SiteUsInfoEditComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},

            { path: 'site_info/add_general_info', name: 'GlobalInfoAdd', component: load('siteInfo/GeneralInfo/GlobalInfoAddFormComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},
            { path: 'site_info/edit_general_info/:id', name: 'GlobalInfoEdit', component: load('siteInfo/GeneralInfo/GlobalInfoEditFormComponent'), meta: {title: 'user page', permissions: [['show', 'site_data']]}},

            { path: 'films', name: 'filmsList', component: load('films/filmsListComponent'), meta: {title: 'user page', permissions: [['show', 'export']]}},
            { path: 'films/add', name: 'filmsAdd', component: load('films/filmsAddComponent'), meta: {title: 'user page', permissions: [['show', 'export']]}},
            { path: 'films/edit/:id', name: 'filmsEdit', component: load('films/filmsEditComponent'), meta: {title: 'user page', permissions: [['show', 'export']]}},

            { path: 'mount_massive/edit/:id', name: 'mount_massive_edit', component: load('mountRoutes/mountMassiveEditComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},
            { path: 'mount_massive/add/', name: 'mount_massive_add', component: load('mountRoutes/mountMassiveAddComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},

            { path: 'article/:article_category', name: 'articlelist', component: load('articles/articleListComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},
            { path: 'article/edit/:id', name: 'articleEdit', component: load('articles/articleEditComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},
            { path: 'article/add/:article_category', name: 'articleAdd', component: load('articles/articleAddComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},

            { path: 'region/edit/:id', name: 'region_edit', component: load('regions/RegionEditPageComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},
            { path: 'region/add/', name: 'region_add', component: load('regions/RegionAddPageComponent'), meta: {title: 'user page', permissions: [['show', 'article']]}},

            { path: 'ice_route/add', name: 'iceRouteAdd', component: load('ice/routes/add_route'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'ice_route/edit/:id', name: 'iceRouteEdit', component: load('ice/routes/edit_route'), meta: {title: 'user page', permissions: [['show', 'route']]}},

            { path: 'ice_sector/add', name: 'iceSectorAdd', component: load('ice/sectors/add_sector'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'ice_sector/edit/:id', name: 'iceSectorEdit', component: load('ice/sectors/edit_sector'), meta: {title: 'user page', permissions: [['show', 'route']]}},

            { path: 'routes_and_sectors', name: 'routeAndSectorList', component: load('routes_and_sectors/routeAndSectorListComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},

            { path: 'route/add/:category', name: 'routeAdd', component: load('routes_and_sectors/routes/routeAddComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'route/edit/:id', name: 'routeEdit', component: load('routes_and_sectors/routes/routeEditComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'sector/add/:category', name: 'sectorAdd', component: load('routes_and_sectors/sectors/sectorAddComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'sector/edit/:id', name: 'sectorEdit', component: load('routes_and_sectors/sectors/sectorEditComponent'), props: true, meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'sector/:id/routes_drawing', name: 'sectorRoutesDrawing', component: load('routes_and_sectors/routes/sectorRouteDrawingsEditorComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},

            { path: 'MTP/add', name: 'MTPAdd', component: load('routes_and_sectors/MTP/MTPAddComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},
            { path: 'MTP/edit/:id', name: 'MTPEdit', component: load('routes_and_sectors/MTP/MTPEditComponent'), meta: {title: 'user page', permissions: [['show', 'route']]}},

            { path: 'sector_local_images', name: 'sectorLocalImagesList', component: load('sector_local_images/sectorLocalImagesListComponent'), meta: {title: 'user page', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/add', name: 'sectorLocalImagesListAdd', component: load('sector_local_images/sectorLocalImageAddComponent'), meta: {title: 'user page', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/edit/:id', name: 'sectorLocalImagesListEdit', component: load('sector_local_images/sectorLocalImageEditComponent'), meta: {title: 'user page', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/options/:id', name: 'sectorLocalImagesOptions', component: load('sector_local_images/sectorLocaleImageEditorComponent'), meta: {title: 'user page', permissions: [['show', 'sector_local_image']]}},

            { path: 'local_bisnes', name: 'localBisnesList', component: load('local_bisnes/localBisnesListComponent'), meta: {title: 'user page', permissions: [['show', 'local_bisnes']]}},
            { path: 'local_bisnes/add', name: 'localBisnesAdd', component: load('local_bisnes/localBisnesAddComponent'), meta: {title: 'user page', permissions: [['show', 'local_bisnes']]}},
            { path: 'local_bisnes/edit/:id', name: 'localBisnesEdit', component: load('local_bisnes/localBisnesEditComponent'), meta: {title: 'user page', permissions: [['show', 'local_bisnes']]}},

            { path: 'warehouses', name: 'warehousesList', component: load('warehouses/WarehousesListComponent'), meta: {title: 'user page', permissions: [['show', 'warehouse']]}},
            { path: 'warehouse/options/:id', name: 'warehouseOptions', component: load('warehouses/warehouseOptionsComponent'), meta: {title: 'user page', permissions: [['show', 'warehouse']]}},

            { path: 'live_cameras', name: 'liveCamerasList', component: load('live_camera/LiveCamerasListComponent'), meta: {title: 'user page', permissions: [['show', 'live_camera']]}},

            { path: 'export_tips', name: 'exportTips', component: load('expoet/exportComponent'), meta: {title: 'user page', permissions: [['show', 'export']]}},

            { path: 'event', name: 'eventList', component: load('event/eventsAndCompetitionsListComponent'), meta: {title: 'user page', permissions: [['show', 'event']]}},
            { path: 'event/add', name: 'eventAdd', component: load('event/events/eventAddComponent'), meta: {title: 'user page', permissions: [['show', 'event']]}},
            { path: 'event/edit/:id', name: 'eventEdit', component: load('event/events/eventEditComponent'), meta: {title: 'user page', permissions: [['show', 'event']]}},

            { path: 'competition/add', name: 'competitionAdd', component: load('event/competitions/competitionAddComponent'), meta: {title: 'user page', permissions: [['show', 'event']]}},
            { path: 'competition/edit/:id', name: 'competitionEdit', component: load('event/competitions/competitionEditComponent'), meta: {title: 'user page', permissions: [['show', 'event']]}},

            { path: 'products_and_categories', name: 'productsList', component: load('products_and_categories/productsAndCatgoriesListComponent'), meta: {title: 'user page', permissions: [['show', 'product'], ['show', 'product_category'], ['show', 'product_option'], ['show', 'sale_code']]}},

            { path: 'product/edit/:id', name: 'productEdit', component: load('products_and_categories/products/productEditComponent'), meta: {title: 'user page', permissions: [['show', 'product']]}},
            { path: 'product/add', name: 'productAdd', component: load('products_and_categories/products/productAddComponent'), meta: {title: 'user page', permissions: [['show', 'product']]}},

            { path: 'product_brands', name: 'product_brands', component: load('products_and_categories/brands/product_brands'), meta: {title: 'user page', permissions: [['show', 'product']]}},
            { path: 'product_option_control/:id', name: 'productOptionsControl', component: load('products_and_categories/product_options/productOptionControlPageComponent'), meta: {title: 'user page', permissions: [['show', 'product_option']]}},

            { path: 'productCategory/edit/:id', name: 'productCategoryEdit', component: load('products_and_categories/categories/productCategoryEditComponent'), meta: {title: 'user page', permissions: [['show', 'product_category']]}},
            { path: 'productCategory/add/', name: 'productCategoryAdd', component: load('products_and_categories/categories/productCategoryAddComponent'), meta: {title: 'user page', permissions: [['show', 'product_category']]}},

            { path: 'user_tours_reservations_list', name: 'userToursReservations', component: load('tour/list/for_user/UserToursReservationsPage'), meta: {title: 'user page'}},
            { path: 'user_tours_list', name: 'userToursList', component: load('tour/list/for_user/UserToursListPage'), meta: {title: 'user page'}},

            { path: 'all_tours_list', name: 'allToursList', component: load('tour/list/for_admin/AllToursListPage'), meta: {title: 'user page', permissions: [['show', 'tour'], ['show', 'tour_category']]}},
            { path: 'all_tours_reservations_list', name: 'allToursReservations', component: load('tour/list/for_admin/AllToursReservationsPage'), meta: {title: 'user page', permissions: [['show', 'tour_reservation']]}},

            { path: 'tour/edit/:id', name: 'tourEdit', component: load('tour/forms/EditTourPage'), meta: {title: 'user page', permissions: [['show', 'tour']]}},
            { path: 'tour/add', name: 'tourAdd', component: load('tour/forms/AddTourPage'), meta: {title: 'user page', permissions: [['show', 'tour']]}},

            { path: 'stor_slides_list', name: 'storSlidesList', component: load('gallery/StoreSlidesPage'), meta: {title: 'user page', permissions: [['show', 'head_slider']]}},

            { path: 'non_registered_commenter_list', name: 'nonRegisteredCommenterList', component: load('comments_&_reviews/NonRegisteredCommenterListPage'), meta: {title: 'user page', permissions: [['show', 'commenter']]}},

            { path: 'services_list', name: 'servicesList', component: load('services/servicesListComponent'), meta: {title: 'user page', permissions: [['show', 'services']]}},
            { path: 'service/edit/:id', name: 'serviceEdit', component: load('services/serviceEditComponent'), meta: {title: 'user page', permissions: [['show', 'services']]}},
            { path: 'service/add/', name: 'serviceAdd', component: load('services/serviceAddComponent'), meta: {title: 'user page', permissions: [['show', 'services']]}},

            { path: 'multimedia', name: 'multimedia', component: load('database_&_multimedia/DatabasePage'), meta: {title: 'user page', permissions: [['show', 'export']]}},

            { path: 'notification_analytics', name: 'notificationAnalytics', component: load('notifications/NotificationAnalyticsPage'), meta: {title: 'user page', permissions: [['show', 'user_notification']]}},

            { path: 'summits', name: 'summitList', component: load('summits/SummitListPage'), meta: {title: 'user page', permissions: [['show', 'summit']]}},
            { path: 'summits/add', name: 'summitAdd', component: load('summits/SummitAddPage'), meta: {title: 'user page', permissions: [['show', 'summit']]}},
            { path: 'summits/edit/:id', name: 'summitEdit', component: load('summits/SummitEditPage'), meta: {title: 'user page', permissions: [['show', 'summit']]}},
            { path: 'my_ascents', name: 'myAscents', component: load('myAscents/MyAscentsListPage'), meta: {title: 'user page'}},
            { path: 'my_donations', name: 'myDonations', component: load('donations/MyDonationsPageComponent'), meta: {title: 'user page'}},

            { path: 'banned', name: 'banned', component: BannedPage, meta: { title: 'Account Suspended' } },

            { path: 'terms_of_use', name: 'user_terms', component: load('TermsOfUsePage'), meta: { title: 'Terms of Use' } },

            { path: 'login', name: 'login', component: login, meta: {title: 'user page'}},
            { path: 'register', name: 'register', component: register ,meta: {title: 'user page'}},
            { path: 'forget_pass', name: 'forget_pass', component: forget_pass ,meta: {title: 'user page'}},
            { path: 'reset-password/:token/:user_id', name: 'reset_pass', component: reset_pass ,meta: {title: 'user page'}},
            { path: 'create_password/:email', name: 'create_pass', component: create_password ,meta: {title: 'user page'}},
            { path: 'login/:provaider/callback', name: 'callback', component: callback_password ,meta: {title: 'user page'}},
            { path: 'email/verify/:user_id/:hash', name: 'verify', component: verify ,meta: {title: 'user page'}},

            { path: "404",    name: "not-found",    component: NotFound, meta: { title: 'Not Found' } },
            { path: "403",    name: "forbidden",    component: NotFound, meta: { title: 'Forbidden' } },
            { path: "500",    name: "server-error", component: NotFound, meta: { title: 'Server Error' } },
        ],
    },
    { path: '/:pathMatch(.*)*', component: NotFound, beforeEnter: () => { window.location.replace('/404'); return false; } },
];

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

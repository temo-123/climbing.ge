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

            { path: 'shop_task_panel', name: 'shopTaskPanel', component: load('dashboards/shop/ShopTaskPanelComponent'), meta: {title: 'Shop Tasks', permissions: [['show', 'shop_task']]}},
            { path: 'shop_dashboard_page', name: 'shopDashboardPage', component: load('dashboards/shop/ShopDashboardPageComponent'), meta: {title: 'Shop Dashboard', permissions: [['show', 'shop_task']]}},
            { path: 'shop_worker_tasks_page', name: 'shopWorkerTasksPage', component: load('dashboards/shop/WorkerTaskPanelPageComponent'), meta: {title: 'My Shop Tasks', permissions: [['show', 'shop_task']]}},

            { path: 'guide_task_panel', name: 'guideTaskPanel', component: load('dashboards/guide/GuideTaskPanelComponent'), meta: {title: 'Guide Tasks', permissions: [['show', 'guide_task']]}},
            { path: 'guide_dashboard_page', name: 'guideDashboardPage', component: load('dashboards/guide/GuideDashboardPageComponent'), meta: {title: 'Guide Dashboard', permissions: [['show', 'guide_task']]}},
            { path: 'team_member_tasks_page', name: 'teamMemberTasksPage', component: load('dashboards/guide/TeamMembersTaskPanelPageComponent'), meta: {title: 'My Guide Tasks', permissions: [['show', 'team']]}},

            { path: 'purchases', name: 'userPurchases', component: load('orders/PurchasesPage'), meta: {title: 'My Purchases'}},
            { path: 'order/user_orders', name: 'userOrders', component: load('orders/PurchasesPage'), meta: {title: 'My Orders'}},
            { path: 'order/all_orders', name: 'allOrders', component: load('orders/allOrderPageComponent'), meta: {title: 'All Orders', permissions: [['show', 'order']]}},
            // Minimal sale-creation entry point for warehouse-restricted sellers — the
            // full Orders page requires 'show'/'order' just to load its order list, which
            // a 'warehouse'/'sell_own' user should never see. This route/page skips that
            // entirely and just exposes the same AddCustomOrderModal.
            { path: 'order/quick_sale', name: 'quickSale', component: load('orders/QuickSaleComponent'), meta: {title: 'Quick Sale', permissions: [['sell_own', 'warehouse'], ['add', 'order']]}},

            { path: 'order/decloration/order_decloration/:payment/:adres/:discount?', name: 'orderDecloration', component: load('orders/decloration/orderDeclorationPageComponent'), meta: {title: 'Order Summary'}},
            { path: 'order/decloration/order_payment', name: 'orderPayment', component: load('orders/decloration/orderPaymentPageComponent'), meta: {title: 'Checkout'}},
            { path: 'order/decloration/confirm_order/:order_decloration?', name: 'confirmOrder', component: load('orders/decloration/confirmOrderPageComponent'), meta: {title: 'Order Placed'}},
            { path: 'payment/success', name: 'paymentSuccess', component: load('orders/PaymentSuccessPage'), meta: {title: 'Payment Success'}},
            { path: 'payment/cancel',  name: 'paymentCancel',  component: load('orders/PaymentCancelPage'),  meta: {title: 'Payment Cancelled'}},

            { path: 'order/confirm_order/:order_id/user/:user_id/token/:token', name: 'mailConfirmOrder', component: load('orders/decloration/mailOrderConfirmComponent'), meta: {title: 'Confirm Order'}},

            { path: 'posts', name: 'posts', component: load('posts/PostsListComponent'), meta: {title: 'Blog Posts', permissions: [['show', 'post']]}},
            { path: 'post/add', name: 'add_post', component: load('posts/AddPostFormComponent'), meta: {title: 'Add Post', permissions: [['show', 'post']]}},
            { path: 'post/edit/:id', name: 'edit_post', component: load('posts/EditPostFormComponent'), meta: {title: 'Edit Post', permissions: [['show', 'post']]}},

            { path: 'cart', name: 'cart', component: load('cartPageComponent'), meta: {title: 'Cart'}},
            { path: 'head_sliders_images', name: 'head_sliders_images', component: load('gallery/HeadSlidersPage'), meta: {title: 'Head Slider Images', permissions: [['show', 'head_slider']]}},
            { path: 'options', name: 'options', component: load('optionsPageComponent'), meta: {title: 'Options'}},
            { path: 'users_list', name: 'usersList', component: load('usersListPageComponent'), meta: {title: 'Users', permissions: [['show', 'user']]}},

            { path: 'comments_and_reviews', name: 'commentsAndReviewsList', component: load('comments_&_reviews/all_actions/comments_&_reviews_page'), meta: {title: 'Comments & Reviews', permissions: [['show', 'comment']]}},
            { path: 'all_routes_reviews', name: 'allRoutesReviews', component: load('comments_&_reviews/all_actions/all_routes_reviews_page'), meta: {title: 'All Route Reviews', permissions: [['show', 'comment']]}},

            { path: 'my_comments_and_reviews', name: 'myCommentsAndReviewsList', component: load('comments_&_reviews/my_actions/my_comments_&_reviews_page'), meta: {title: 'My Comments & Reviews'}},
            { path: 'my_routes_review', name: 'myRoutesReview', component: load('comments_&_reviews/my_actions/my_routes_review_page'), meta: {title: 'My Route Reviews'}},

            { path: 'favorite_films', name: 'favoriteFilmsPage', component: load('favorites/FavoriteFilmsPageComponent'), meta: {title: 'Favorite Films'}},
            { path: 'my_favorite_outdoor_area', name: 'favoriteOutdoorArea', component: load('favorites/FavoriteOutdoorAreaPageComponent'), meta: {title: 'Favorite Outdoor Areas'}},
            { path: 'interested_events', name: 'interestedEvents', component: load('favorites/InterestedEventsPageComponent'), meta: {title: 'Interested Events'}},
            { path: 'favorite_products', name: 'favoriteProducts', component: load('favorites/FavoriteProductsPageComponent'), meta: {title: 'Favorite Products'}},

            { path: 'site_info', name: 'siteInfo', component: load('siteInfo/siteInfoPageComponent'), meta: {title: 'Site Info', permissions: [['show', 'site_data']]}},

            { path: 'site_info/site_data_edit', name: 'siteDataEdit', component: load('siteInfo/SiteData/SiteInfoEditComponent'), meta: {title: 'Edit Site Data', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_data_edit', name: 'siteGlobalDataEdit', component: load('siteInfo/SiteData/SiteGlobalInfoEditComponent'), meta: {title: 'Edit Global Info', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_ka_edit', name: 'siteKaDataEdit', component: load('siteInfo/SiteData/SiteKaInfoEditComponent'), meta: {title: 'Edit Georgian Info', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_ru_edit', name: 'siteRuDataEdit', component: load('siteInfo/SiteData/SiteRuInfoEditComponent'), meta: {title: 'Edit Russian Info', permissions: [['show', 'site_data']]}},
            { path: 'site_info/site_global_us_edit', name: 'siteUsDataEdit', component: load('siteInfo/SiteData/SiteUsInfoEditComponent'), meta: {title: 'Edit English Info', permissions: [['show', 'site_data']]}},

            { path: 'site_info/add_general_info', name: 'GlobalInfoAdd', component: load('siteInfo/GeneralInfo/GlobalInfoAddFormComponent'), meta: {title: 'Add General Info', permissions: [['show', 'site_data']]}},
            { path: 'site_info/edit_general_info/:id', name: 'GlobalInfoEdit', component: load('siteInfo/GeneralInfo/GlobalInfoEditFormComponent'), meta: {title: 'Edit General Info', permissions: [['show', 'site_data']]}},

            { path: 'films', name: 'filmsList', component: load('films/filmsListComponent'), meta: {title: 'Films', permissions: [['show', 'film']]}},
            { path: 'films/add', name: 'filmsAdd', component: load('films/filmsAddComponent'), meta: {title: 'Add Film', permissions: [['show', 'film']]}},
            { path: 'films/edit/:id', name: 'filmsEdit', component: load('films/filmsEditComponent'), meta: {title: 'Edit Film', permissions: [['show', 'film']]}},

            { path: 'mount_massive/edit/:id', name: 'mount_massive_edit', component: load('mountRoutes/mountMassiveEditComponent'), meta: {title: 'Edit Mountain Massif', permissions: [['show', 'mount_massive']]}},
            { path: 'mount_massive/add/', name: 'mount_massive_add', component: load('mountRoutes/mountMassiveAddComponent'), meta: {title: 'Add Mountain Massif', permissions: [['show', 'mount_massive']]}},

            { path: 'article/:article_category', name: 'articlelist', component: load('articles/articleListComponent'), meta: {title: 'Articles', permissions: [['show', 'article']]}},
            { path: 'article/edit/:id', name: 'articleEdit', component: load('articles/articleEditComponent'), meta: {title: 'Edit Article', permissions: [['show', 'article']]}},
            { path: 'article/add/:article_category', name: 'articleAdd', component: load('articles/articleAddComponent'), meta: {title: 'Add Article', permissions: [['show', 'article']]}},
            { path: 'spot_rock_image/options/:id', name: 'spotRockImageEditor', component: load('articles/spotRockImageEditorComponent'), meta: {title: 'Spot/Rock Image Editor', permissions: [['show', 'sector']]}},

            { path: 'region/edit/:id', name: 'region_edit', component: load('regions/RegionEditPageComponent'), meta: {title: 'Edit Region', permissions: [['show', 'region']]}},
            { path: 'region/add/', name: 'region_add', component: load('regions/RegionAddPageComponent'), meta: {title: 'Add Region', permissions: [['show', 'region']]}},

            { path: 'ice_route/add', name: 'iceRouteAdd', component: load('ice/routes/add_route'), meta: {title: 'Add Ice Route', permissions: [['show', 'route']]}},
            { path: 'ice_route/edit/:id', name: 'iceRouteEdit', component: load('ice/routes/edit_route'), meta: {title: 'Edit Ice Route', permissions: [['show', 'route']]}},

            { path: 'ice_sector/add', name: 'iceSectorAdd', component: load('ice/sectors/add_sector'), meta: {title: 'Add Ice Sector', permissions: [['show', 'route']]}},
            { path: 'ice_sector/edit/:id', name: 'iceSectorEdit', component: load('ice/sectors/edit_sector'), meta: {title: 'Edit Ice Sector', permissions: [['show', 'route']]}},

            { path: 'routes_and_sectors', name: 'routeAndSectorList', component: load('routes_and_sectors/routeAndSectorListComponent'), meta: {title: 'Routes & Sectors', permissions: [['show', 'route']]}},

            { path: 'route/add/:category', name: 'routeAdd', component: load('routes_and_sectors/routes/routeAddComponent'), meta: {title: 'Add Route', permissions: [['show', 'route']]}},
            { path: 'route/edit/:id', name: 'routeEdit', component: load('routes_and_sectors/routes/routeEditComponent'), meta: {title: 'Edit Route', permissions: [['show', 'route']]}},
            { path: 'sector/add/:category', name: 'sectorAdd', component: load('routes_and_sectors/sectors/sectorAddComponent'), meta: {title: 'Add Sector', permissions: [['show', 'route']]}},
            { path: 'sector/edit/:id', name: 'sectorEdit', component: load('routes_and_sectors/sectors/sectorEditComponent'), props: true, meta: {title: 'Edit Sector', permissions: [['show', 'route']]}},
            { path: 'sector/:id/routes_drawing', name: 'sectorRoutesDrawing', component: load('routes_and_sectors/routes/sectorRouteDrawingsEditorComponent'), meta: {title: 'Sector Route Drawing', permissions: [['show', 'route']]}},

            { path: 'MTP/add', name: 'MTPAdd', component: load('routes_and_sectors/MTP/MTPAddComponent'), meta: {title: 'Add MTP', permissions: [['show', 'route'], ['show', 'mtp']]}},
            { path: 'MTP/edit/:id', name: 'MTPEdit', component: load('routes_and_sectors/MTP/MTPEditComponent'), meta: {title: 'Edit MTP', permissions: [['show', 'route'], ['show', 'mtp']]}},

            { path: 'sector_local_images', name: 'sectorLocalImagesList', component: load('sector_local_images/sectorLocalImagesListComponent'), meta: {title: 'Sector Local Images', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/add', name: 'sectorLocalImagesListAdd', component: load('sector_local_images/sectorLocalImageAddComponent'), meta: {title: 'Add Sector Local Image', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/edit/:id', name: 'sectorLocalImagesListEdit', component: load('sector_local_images/sectorLocalImageEditComponent'), meta: {title: 'Edit Sector Local Image', permissions: [['show', 'sector_local_image']]}},
            { path: 'sector_local_images/options/:id', name: 'sectorLocalImagesOptions', component: load('sector_local_images/sectorLocaleImageEditorComponent'), meta: {title: 'Sector Local Image Options', permissions: [['show', 'sector_local_image']]}},

            { path: 'local_bisnes', name: 'localBisnesList', component: load('local_bisnes/localBisnesListComponent'), meta: {title: 'Local Businesses', permissions: [['show', 'local_bisnes']]}},
            { path: 'local_bisnes/add', name: 'localBisnesAdd', component: load('local_bisnes/localBisnesAddComponent'), meta: {title: 'Add Local Business', permissions: [['show', 'local_bisnes']]}},
            { path: 'local_bisnes/edit/:id', name: 'localBisnesEdit', component: load('local_bisnes/localBisnesEditComponent'), meta: {title: 'Edit Local Business', permissions: [['show', 'local_bisnes']]}},

            { path: 'warehouses', name: 'warehousesList', component: load('warehouses/WarehousesListComponent'), meta: {title: 'Warehouses', permissions: [['show', 'warehouse']]}},
            { path: 'warehouse/options/:id', name: 'warehouseOptions', component: load('warehouses/warehouseOptionsComponent'), meta: {title: 'Warehouse Options', permissions: [['show', 'warehouse']]}},

            { path: 'live_cameras', name: 'liveCamerasList', component: load('live_camera/LiveCamerasListComponent'), meta: {title: 'Live Cameras', permissions: [['show', 'live_camera']]}},

            { path: 'export_tips', name: 'exportTips', component: load('expoet/exportComponent'), meta: {title: 'Export Tips', permissions: [['show', 'export']]}},

            { path: 'event', name: 'eventList', component: load('event/eventsAndCompetitionsListComponent'), meta: {title: 'Events & Competitions', permissions: [['show', 'event']]}},
            { path: 'event/add', name: 'eventAdd', component: load('event/events/eventAddComponent'), meta: {title: 'Add Event', permissions: [['show', 'event']]}},
            { path: 'event/edit/:id', name: 'eventEdit', component: load('event/events/eventEditComponent'), meta: {title: 'Edit Event', permissions: [['show', 'event']]}},

            { path: 'competition/add', name: 'competitionAdd', component: load('event/competitions/competitionAddComponent'), meta: {title: 'Add Competition', permissions: [['show', 'event']]}},
            { path: 'competition/edit/:id', name: 'competitionEdit', component: load('event/competitions/competitionEditComponent'), meta: {title: 'Edit Competition', permissions: [['show', 'event']]}},

            { path: 'products_and_categories', name: 'productsList', component: load('products_and_categories/productsAndCatgoriesListComponent'), meta: {title: 'Products & Categories', permissions: [['show', 'product'], ['show', 'product_category'], ['show', 'product_option'], ['show', 'sale_code']]}},

            { path: 'product/edit/:id', name: 'productEdit', component: load('products_and_categories/products/productEditComponent'), meta: {title: 'Edit Product', permissions: [['show', 'product']]}},
            { path: 'product/add', name: 'productAdd', component: load('products_and_categories/products/productAddComponent'), meta: {title: 'Add Product', permissions: [['show', 'product']]}},

            { path: 'product_brands', name: 'product_brands', component: load('products_and_categories/brands/product_brands'), meta: {title: 'Product Brands', permissions: [['show', 'product']]}},
            { path: 'product_option_control/:id', name: 'productOptionsControl', component: load('products_and_categories/product_options/productOptionControlPageComponent'), meta: {title: 'Product Options', permissions: [['show', 'product_option']]}},

            { path: 'productCategory/edit/:id', name: 'productCategoryEdit', component: load('products_and_categories/categories/productCategoryEditComponent'), meta: {title: 'Edit Product Category', permissions: [['show', 'product_category']]}},
            { path: 'productCategory/add/', name: 'productCategoryAdd', component: load('products_and_categories/categories/productCategoryAddComponent'), meta: {title: 'Add Product Category', permissions: [['show', 'product_category']]}},

            { path: 'user_tours_reservations_list', name: 'userToursReservations', component: load('tour/list/for_user/UserToursReservationsPage'), meta: {title: 'My Tour Reservations'}},
            { path: 'user_tours_list', name: 'userToursList', component: load('tour/list/for_user/UserToursListPage'), meta: {title: 'My Tours'}},

            { path: 'all_tours_list', name: 'allToursList', component: load('tour/list/for_admin/AllToursListPage'), meta: {title: 'All Tours', permissions: [['show', 'tour'], ['show', 'tour_category']]}},
            { path: 'all_tours_reservations_list', name: 'allToursReservations', component: load('tour/list/for_admin/AllToursReservationsPage'), meta: {title: 'All Tour Reservations', permissions: [['show', 'tour_reservation']]}},

            { path: 'tour/edit/:id', name: 'tourEdit', component: load('tour/forms/EditTourPage'), meta: {title: 'Edit Tour', permissions: [['show', 'tour']]}},
            { path: 'tour/add', name: 'tourAdd', component: load('tour/forms/AddTourPage'), meta: {title: 'Add Tour', permissions: [['show', 'tour']]}},

            { path: 'stor_slides_list', name: 'storSlidesList', component: load('gallery/StoreSlidesPage'), meta: {title: 'Store Slides', permissions: [['show', 'head_slider']]}},

            { path: 'non_registered_commenter_list', name: 'nonRegisteredCommenterList', component: load('comments_&_reviews/NonRegisteredCommenterListPage'), meta: {title: 'Non-Registered Commenters', permissions: [['show', 'commenter']]}},

            { path: 'services_list', name: 'servicesList', component: load('services/servicesListComponent'), meta: {title: 'Services', permissions: [['show', 'services']]}},
            { path: 'service/edit/:id', name: 'serviceEdit', component: load('services/serviceEditComponent'), meta: {title: 'Edit Service', permissions: [['show', 'services']]}},
            { path: 'service/add/', name: 'serviceAdd', component: load('services/serviceAddComponent'), meta: {title: 'Add Service', permissions: [['show', 'services']]}},

            { path: 'multimedia', name: 'multimedia', component: load('database_&_multimedia/DatabasePage'), meta: {title: 'Database & Multimedia', permissions: [['show', 'database'], ['show', 'export']]}},

            { path: 'notification_analytics', name: 'notificationAnalytics', component: load('notifications/NotificationAnalyticsPage'), meta: {title: 'Notification Analytics', permissions: [['show', 'user_notification']]}},

            { path: 'mail', name: 'mailPage', component: load('notifications/MailPageComponent'), meta: {title: 'Mail', permissions: [['show', 'mail']]}},

            { path: 'trainings', name: 'trainingsList', component: load('trainings/TrainingsListComponent'), meta: {title: 'Trainings', permissions: [['show', 'training']]}},
            { path: 'training/add', name: 'trainingAdd', component: load('trainings/TrainingAddComponent'), meta: {title: 'Add Training', permissions: [['add', 'training']]}},
            { path: 'training/edit/:id', name: 'trainingEdit', component: load('trainings/TrainingEditComponent'), meta: {title: 'Edit Training', permissions: [['edit', 'training']]}},

            { path: 'training_plans', name: 'trainingPlansList', component: load('trainings/TrainingPlansListComponent'), meta: {title: 'Training Plans', permissions: [['show', 'training_plan']]}},
            { path: 'training_plan/add', name: 'trainingPlanAdd', component: load('trainings/TrainingPlanAddComponent'), meta: {title: 'Add Training Plan', permissions: [['add', 'training_plan']]}},
            { path: 'training_plan/edit/:id', name: 'trainingPlanEdit', component: load('trainings/TrainingPlanEditComponent'), meta: {title: 'Edit Training Plan', permissions: [['edit', 'training_plan']]}},

            { path: 'partner_organizations', name: 'partnerOrganizationList', component: load('partner_organizations/partnerOrganizationListComponent'), meta: {title: 'Partner Organizations', permissions: [['show', 'partner_organization']]}},
            { path: 'partner_organization/add', name: 'partnerOrganizationAdd', component: load('partner_organizations/organizationAddComponent'), meta: {title: 'Add Partner Organization', permissions: [['add', 'partner_organization']]}},
            { path: 'partner_organization/edit/:id', name: 'partnerOrganizationEdit', component: load('partner_organizations/organizationEditComponent'), meta: {title: 'Edit Partner Organization', permissions: [['edit', 'partner_organization']]}},
            { path: 'partner_organization_member/add', name: 'partnerOrganizationMemberAdd', component: load('partner_organizations/memberAddComponent'), meta: {title: 'Add Organization Member', permissions: [['add', 'partner_organization']]}},
            { path: 'partner_organization_member/edit/:id', name: 'partnerOrganizationMemberEdit', component: load('partner_organizations/memberEditComponent'), meta: {title: 'Edit Organization Member', permissions: [['edit', 'partner_organization']]}},

            { path: 'summits', name: 'summitList', component: load('summits/SummitListPage'), meta: {title: 'Summits', permissions: [['show', 'summit']]}},
            { path: 'summits/add', name: 'summitAdd', component: load('summits/SummitAddPage'), meta: {title: 'Add Summit', permissions: [['show', 'summit']]}},
            { path: 'summits/edit/:id', name: 'summitEdit', component: load('summits/SummitEditPage'), meta: {title: 'Edit Summit', permissions: [['show', 'summit']]}},
            { path: 'my_ascents', name: 'myAscents', component: load('myAscents/MyAscentsListPage'), meta: {title: 'My Ascents'}},
            { path: 'my_donations', name: 'myDonations', component: load('donations/MyDonationsPageComponent'), meta: {title: 'My Donations'}},
            { path: 'my_trainings', name: 'myTrainings', component: load('MyTrainingsPageComponent'), meta: {title: 'My Trainings'}},
            { path: 'my_organization', name: 'myOrganization', component: load('MyOrganizationPageComponent'), meta: {title: 'My Organization'}},

            { path: 'banned', name: 'banned', component: BannedPage, meta: { title: 'Account Suspended' } },

            { path: 'terms_of_use', name: 'user_terms', component: load('TermsOfUsePage'), meta: { title: 'Terms of Use' } },

            { path: 'login', name: 'login', component: login, meta: {title: 'Login'}},
            { path: 'register', name: 'register', component: register ,meta: {title: 'Register'}},
            { path: 'forget_pass', name: 'forget_pass', component: forget_pass ,meta: {title: 'Forgot Password'}},
            { path: 'reset-password/:token/:user_id', name: 'reset_pass', component: reset_pass ,meta: {title: 'Reset Password'}},
            { path: 'create_password/:email', name: 'create_pass', component: create_password ,meta: {title: 'Create Password'}},
            { path: 'login/:provaider/callback', name: 'callback', component: callback_password ,meta: {title: 'Signing In'}},
            { path: 'email/verify/:user_id/:hash', name: 'verify', component: verify ,meta: {title: 'Verify Email'}},

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

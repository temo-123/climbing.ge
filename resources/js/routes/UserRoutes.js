import VueRouter from 'vue-router'

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
const router = new VueRouter({
    routes: [
        { path: '/', name: 'home', component: load('HomePageComponent') },
        { path: '/admin_page', name: 'adminPage', component: load('AdminPageComponent'), 
        meta: {
            can: 'show dashboard',
            fail: '/'
        }},

        { path: '/task_panel', name: 'taskPanel', component: load('WorkerTaskPanelPageComponent') },

        { path: '/purchases', name: 'userPurchases', component: load('PurchasesPage') },
        { path: '/order/user_orders', name: 'userOrders', component: load('orders/userOrderComponent') },
        { path: '/order/all_orders', name: 'allOrders', component: load('orders/allOrderPageComponent') },

        { path: '/order/decloration/order_decloration/', name: 'orderDecloration', component: load('orders/decloration/orderDeclorationPageComponent') },
        { path: '/order/decloration/order_payment/', name: 'orderPayment', component: load('orders/decloration/orderPaymentPageComponent'), },
        { path: '/order/decloration/confirm_order/', name: 'confirmOrder', component: load('orders/decloration/confirmOrderPageComponent') },
        
        { path: '/order/confirm_order/:order_id/user/:user_id', name: 'mailConfirmOrder', component: load('orders/decloration/mailOrderConfirmComponent') },

        { path: '/my_posts', name: 'myPosts', component: load('posts/MyPostsPageCompoents') },
        { path: '/all_posts', name: 'allPosts', component: load('posts/AllPostsPageComponent') },

        { path: '/cart', name: 'cart', component: load('cartPageComponent') },
        { path: '/head_sliders_images', name: 'head_sliders_images', component: load('gallery/HeadSlidersPage') },
        { path: '/site_folloers', name: 'site_folloers', component: load('SiteFolloersListPageComponent') },
        { path: '/options', name: 'options', component: load('optionsPageComponent') },
        { path: '/users_list', name: 'usersList', component: load('usersListPageComponent') },

        { path: '/comments_and_reviews', name: 'commentsAndReviewsList', component: load('comments_&_reviews/all_actions/comments_&_reviews_page') },
        // { path: '/reviews', name: 'reviewsList', component: load('comments_&_reviews/reviews/ReviewPageComponent') },

        { path: '/my_comments_and_reviews', name: 'myCommentsAndReviewsList', component: load('comments_&_reviews/my_actions/my_comments_&_reviews_page') },
        // { path: '/myReviews', name: 'myReviewsList', component: load('comments_&_reviews/reviews/MyReviewPageComponent') },

        { path: '/favorite_films', name: 'favoriteFilmsPage', component: load('favorites/FavoriteFilmsPageComponent') },
        { path: '/my_favorite_outdoor_area', name: 'favoriteOutdoorArea', component: load('favorites/FavoriteOutdoorAreaPageComponent') },
        { path: '/interested_events', name: 'interestedEvents', component: load('favorites/InterestedEventsPageComponent') },
        { path: '/favorite_products', name: 'favoriteProducts', component: load('favorites/FavoriteProductsPageComponent') },

        { path: '/site_info', name: 'siteInfo', component: load('siteInfo/siteInfoPageComponent') },

        { path: '/site_info/site_data_edit', name: 'siteDataEdit', component: load('siteInfo/SiteData/SiteInfoEditComponent') },
        { path: '/site_info/site_global_data_edit', name: 'siteGlobalDataEdit', component: load('siteInfo/SiteData/SiteGlobalInfoEditComponent') },
        { path: '/site_info/site_global_ka_edit', name: 'siteKaDataEdit', component: load('siteInfo/SiteData/SiteKaInfoEditComponent') },
        { path: '/site_info/site_global_ru_edit', name: 'siteRuDataEdit', component: load('siteInfo/SiteData/SiteRuInfoEditComponent') },
        { path: '/site_info/site_global_us_edit', name: 'siteUsDataEdit', component: load('siteInfo/SiteData/SiteUsInfoEditComponent') },

        { path: '/site_info/add_general_info', name: 'GlobalInfoAdd', component: load('siteInfo/GeneralInfo/GlobalInfoAddFormComponent') },
        { path: '/site_info/edit_general_info/:id', name: 'GlobalInfoEdit', component: load('siteInfo/GeneralInfo/GlobalInfoEditFormComponent') },

        { path: '/films', name: 'filmsList', component: load('films/filmsListComponent') },
        { path: '/films/add', name: 'filmsAdd', component: load('films/filmsAddComponent') },
        { path: '/films/edit/:id', name: 'filmsEdit', component: load('films/filmsEditComponent') },

        { path: '/mount_routes/mount_routes_list', name: 'mountlist', component: load('mountRoutes/mountListComponent') },
        { path: '/mount_massive/edit/:id', name: 'mount_massive_edit', component: load('mountRoutes/mountMassiveEditComponent') },
        { path: '/mount_massive/add/', name: 'mount_massive_add', component: load('mountRoutes/mountMassiveAddComponent') },

        { path: '/article/:article_category', name: 'articlelist', component: load('articles/articleListComponent') },
        { path: '/article/edit/:id', name: 'articleEdit', component: load('articles/articleEditComponent') },
        { path: '/article/add/:article_category', name: 'articleAdd', component: load('articles/articleAddComponent') },
   
        { path: '/spot_category/edit/:id', name: 'spot_category_edit', component: load('spots_categories/SpotsCategoriesEditPageComponent') },
        { path: '/spot_category/add/', name: 'spot_category_add', component: load('spots_categories/SpotsCategoriesAddPageComponent') },

        { path: '/ice_route/add', name: 'iceRouteAdd', component: load('ice/routes/add_route') },
        { path: '/ice_route/edit/:id', name: 'iceRouteEdit', component: load('ice/routes/edit_route') },

        { path: '/ice_sector/add', name: 'iceSectorAdd', component: load('ice/sectors/add_sector') },
        { path: '/ice_sector/edit/:id', name: 'iceSectorEdit', component: load('ice/sectors/edit_sector') },

        { path: '/routes_and_sectors', name: 'routeAndSectorList', component: load('routes_and_sectors/routeAndSectorListComponent') },

        { path: '/route/add', name: 'routeAdd', component: load('routes_and_sectors/routes/routeAddComponent') },
        { path: '/route/edit/:id', name: 'routeEdit', component: load('routes_and_sectors/routes/routeEditComponent') },

        { path: '/sector/add', name: 'sectorAdd', component: load('routes_and_sectors/sectors/sectorAddComponent') },
        { path: '/sector/edit/:id', name: 'sectorEdit', component: load('routes_and_sectors/sectors/sectorEditComponent') },

        { path: '/MTP/add', name: 'MTPAdd', component: load('routes_and_sectors/MTP/MTPAddComponent') },
        { path: '/MTP/edit/:id', name: 'MTPEdit', component: load('routes_and_sectors/MTP/MTPEditComponent') },

        { path: '/MTPPitch/add', name: 'MTPPitchAdd', component: load('routes_and_sectors/MTPPitch/MTPPitchAddComponent') },
        { path: '/MTPPitch/edit/:id', name: 'MTPPitchEdit', component: load('routes_and_sectors/MTPPitch/MTPPitchEditComponent') },

        { path: '/sector_local_images', name: 'sectorLocalImagesList', component: load('sector_local_images/sectorLocalImagesListComponent') },
        { path: '/sector_local_images/add', name: 'sectorLocalImagesListAdd', component: load('sector_local_images/sectorLocalImageAddComponent') },
        { path: '/sector_local_images/edit/:id', name: 'sectorLocalImagesListEdit', component: load('sector_local_images/sectorLocalImageEditComponent') },

        { path: '/local_bisnes', name: 'localBisnesList', component: load('local_bisnes/localBisnesListComponent') },
        { path: '/local_bisnes/add', name: 'localBisnesAdd', component: load('local_bisnes/localBisnesAddComponent') },
        { path: '/local_bisnes/edit/:id', name: 'localBisnesEdit', component: load('local_bisnes/localBisnesEditComponent') },

        { path: '/event', name: 'eventList', component: load('event/eventsAndCompetitionsListComponent') },
        { path: '/event/add', name: 'eventAdd', component: load('event/events/eventAddComponent') },
        { path: '/event/edit/:id', name: 'eventEdit', component: load('event/events/eventEditComponent') },
        
        { path: '/competition/add', name: 'competitionAdd', component: load('event/competitions/competitionAddComponent') },
        { path: '/competition/edit/:id', name: 'competitionEdit', component: load('event/competitions/competitionEditComponent') },
        
        { path: '/user_product_list', name: 'userProductsList', component: load('products_and_categories/list/for_user/userProductsListComponent') },
        { path: '/products_and_categories', name: 'productsList', component: load('products_and_categories/list/for_admin/productsAndCatgoriesListComponent') },
        
        { path: '/product/edit/:id', name: 'productEdit', component: load('products_and_categories/products/productEditComponent') },
        { path: '/product/add', name: 'productAdd', component: load('products_and_categories/products/productAddComponent') },

        { path: '/product_brands', name: 'product_brands', component: load('products_and_categories/brands/product_brands') },
        { path: '/product_option_control/:id', name: 'productOptionsControl', component: load('products_and_categories/productOptionControlPageComponent') }, // :id => :product_id

        { path: '/productCategory/edit/:id', name: 'productCategoryEdit', component: load('products_and_categories/categories/productCategoryEditComponent'), },
        { path: '/productCategory/add/', name: 'productCategoryAdd', component: load('products_and_categories/categories/productCategoryAddComponent') },


        { path: '/user_tours_reservations_list', name: 'userToursReservations', component: load('tour/list/for_user/UserToursReservationsPage') },
        { path: '/user_tours_list', name: 'userToursList', component: load('tour/list/for_user/UserToursListPage') },

        { path: '/all_tours_list', name: 'allToursList', component: load('tour/list/for_admin/AllToursListPage') },
        { path: '/all_tours_reservations_list', name: 'allToursReservations', component: load('tour/list/for_admin/AllToursReservationsPage') },

        { path: '/tour/edit/:id', name: 'tourEdit', component: load('tour/forms/EditTourPage') },
        { path: '/tour/add', name: 'tourAdd', component: load('tour/forms/AddTourPage') },

        { path: '/stor_slides_list', name: 'storSlidesList', component: load('gallery/StoreSlidesPage') },

        { path: '/non_registered_commenter_list', name: 'nonRegisteredCommenterList', component: load('NonRegisteredCommenterListPage') },

        { path: '/services_list', name: 'servicesList', component: load('services/servicesListComponent'), },
        { path: '/service/edit/:id', name: 'serviceEdit', component: load('services/serviceEditComponent'), },
        { path: '/service/add/', name: 'serviceAdd', component: load('services/serviceAddComponent'), },

        { path: '/article_images', name: 'article_images', component: load('multimedia/ArticleImagesPage'), },

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register},
        { path: '/forget_pass', name: 'forget_pass', component: forget_pass},
        { path: '/reset-password/:token/:user_id', name: 'reset_pass', component: reset_pass},
        { path: '/create_password/:email', name: 'create_pass', component: create_password},
        { path: '/login/:provaider/callback', name: 'callback', component: callback_password},
        { path: '/email/verify/:user_id/:hash', name: 'verify', component: verify},

        { path: '*', name: 'NotFound', component: NotFound },
    ],
    mode: 'history',
});

router.beforeEach((to, from, next)=>{
    const token = localStorage.getItem('x_xsrf_token')
    
    axios
        .get('./api/auth_user')
        .then((response)=>{
            // if (token != null) {
            //     localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])
            // }
            check(to, from, next, token)
        })
        .catch(function (error) {
            if (token != null) {
                localStorage.removeItem('x_xsrf_token');
                check(to, from, next, token)
                return next ({name: 'login'})
            }
            else{
                check(to, from, next, token)
            }
        });

    next()
})

function check(to, from, next, token) {

    if (!token) {
        if (to.name == 'login' || to.name == 'register' || to.name == 'forget_pass' || to.name == 'reset_pass' || to.name == 'callback') {
            return next()
        }
        else{
            return next ({name: 'login'})
        }
    }
    else if (token){
        if(to.name === 'login' || to.name === 'register' || to.name === 'forget_pass' || to.name === 'reset_pass' || to.name == 'callback' && token) {
            return next ({name: 'home'})
        }
    }
}

export default router
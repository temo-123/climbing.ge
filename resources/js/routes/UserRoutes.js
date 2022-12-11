import VueRouter from 'vue-router'

import myPostPage from '../components/user/pages/posts/MyPostsPageCompoents.vue'
import allPostPage from '../components/user/pages/posts/AllPostsPageComponent.vue'

import favoriteFilmsPage from '../components/user/pages/favorites/FavoriteFilmsPageComponent.vue'
import favoriteOutdoorArea from '../components/user/pages/favorites/FavoriteOutdoorAreaPageComponent.vue'
import favoriteProductsPage from '../components/user/pages/favorites/FavoriteProductsPageComponent.vue'
import interestedEventsPage from '../components/user/pages/favorites/InterestedEventsPageComponent.vue'

import cartPage from '../components/user/pages/cartPageComponent.vue'
import galleryPage from '../components/user/pages/GalleryPageComponent.vue'
import optionsPage from '../components/user/pages/optionsPageComponent.vue'
import usersList from '../components/user/pages/usersListPageComponent.vue'

import commentsPage from '../components/user/pages/comments/CommentsPageComponent.vue'
import myCommentsPage from '../components/user/pages/comments/MyCommentsPageComponent.vue'

import siteInfoPage from '../components/user/pages/siteInfo/siteInfoPageComponent.vue'

import filmsList from '../components/user/pages/films/filmsListComponent.vue'
import filmsAdd from '../components/user/pages/films/filmsAddComponent.vue'
import filmsEdit from '../components/user/pages/films/filmsEditComponent.vue'

import mountList from '../components/user/pages/mountRoutes/mountListComponent.vue'

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

import NotFound from '../components/errors/404Component.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'


function load(component) {
    return () => import(`../components/user/pages/${component}.vue`)
}
const router = new VueRouter({
    routes: [
        { path: '/', name: 'home', component: load('HomePageComponent') },
        { path: '/admin_page', name: 'adminPage', component: load('AdminPageComponent') },

        { path: '/order/my_orders', name: 'myOrders', component: load('orders/myOrderComponent') },
        { path: '/order/all_orders', name: 'allOrders', component: load('orders/allOrderPageComponent') },

        { path: '/order/decloration/order_decloration/', name: 'orderDecloration', component: load('orders/decloration/orderDeclorationPageComponent') },
        { path: '/order/decloration/order_payment/', name: 'orderPayment', component: load('orders/decloration/orderPaymentPageComponent'), },
        { path: '/order/decloration/confirm_order/', name: 'confirmOrder', component: load('orders/decloration/confirmOrderPageComponent') },
        
        { path: '/order/confirm_order/:order_id/user/:user_id', name: 'confirmOrder', component: load('orders/decloration/mailOrderConfirmComponent') },

        { path: '/my_posts', name: 'myPosts', component: myPostPage },
        { path: '/all_posts', name: 'allPosts', component: allPostPage },

        { path: '/cart', name: 'cart', component: cartPage },
        { path: '/favorite_products', name: 'favoriteProducts', component: favoriteProductsPage },
        { path: '/gallery', name: 'gallery', component: galleryPage },
        { path: '/options', name: 'options', component: optionsPage },
        { path: '/interested_events', name: 'interestedEvents', component: interestedEventsPage },
        { path: '/users_list', name: 'usersList', component: usersList },

        { path: '/comments', name: 'comentsList', component: commentsPage },
        { path: '/myComments', name: 'myComentsList', component: myCommentsPage },

        { path: '/favorite_films', name: 'favoriteFilmsPage', component: favoriteFilmsPage },
        { path: '/my_favorite_outdoor_area', name: 'favoriteOutdoorArea', component: favoriteOutdoorArea },

        { path: '/site_info', name: 'siteInfo', component: siteInfoPage },
        
        { path: '/site_info/site_data_edit', name: 'siteDataEdit', component: load('siteInfo/SiteData/SiteInfoEditComponent') },
        { path: '/site_info/site_global_data_edit', name: 'siteGlobalDataEdit', component: load('siteInfo/SiteData/SiteGlobalInfoEditComponent') },
        { path: '/site_info/site_global_ka_edit', name: 'siteKaDataEdit', component: load('siteInfo/SiteData/SiteKaInfoEditComponent') },
        { path: '/site_info/site_global_ru_edit', name: 'siteRuDataEdit', component: load('siteInfo/SiteData/SiteRuInfoEditComponent') },
        { path: '/site_info/site_global_us_edit', name: 'siteUsDataEdit', component: load('siteInfo/SiteData/SiteUsInfoEditComponent') },

        { path: '/site_info/add_general_info', name: 'GlobalInfoAdd', component: load('siteInfo/GeneralInfo/GlobalInfoAddFormComponent') },
        { path: '/site_info/edit_general_info/:id', name: 'GlobalInfoEdit', component: load('siteInfo/GeneralInfo/GlobalInfoEditFormComponent') },

        { path: '/films', name: 'filmsList', component: filmsList },
        { path: '/films/add', name: 'filmsAdd', component: filmsAdd },
        { path: '/films/edit/:id', name: 'filmsEdit', component: filmsEdit },

        { path: '/mount_routes/mount_routes_list', name: 'mountlist', component: mountList },

        { path: '/article/:article_category', name: 'articlelist', component: articleList },
        { path: '/article/edit/:id', name: 'articleEdit', component: articleEdit },
        { path: '/article/add/:article_category', name: 'articleAdd', component: articleAdd },
   
        { path: '/spot_category/edit/:id', name: 'spot_category_edit', component: load('spots_categories/SpotsCategoriesEditPageComponent') },
        { path: '/spot_category/add/', name: 'spot_category_add', component: load('spots_categories/SpotsCategoriesAddPageComponent') },

        { path: '/mount_massive/edit/:id', name: 'mount_massive_edit', component: load('mountRoutes/mountMassiveEditComponent') },
        { path: '/mount_massive/add/', name: 'mount_massive_add', component: load('mountRoutes/mountMassiveAddComponent') },

        { path: '/routes_and_sectors', name: 'routeAndSectorList', component: routeAndSectorList },

        { path: '/route/add', name: 'routeAdd', component: routeAdd },
        { path: '/route/edit/:id', name: 'routeEdit', component: routeEdit },

        { path: '/sector/add', name: 'sectorAdd', component: sectorAdd },
        { path: '/sector/edit/:id', name: 'sectorEdit', component: sectorEdit },

        { path: '/MTP/add', name: 'MTPAdd', component: MTPAdd },
        { path: '/MTP/edit/:id', name: 'MTPEdit', component: MTPEdit },

        { path: '/MTPPitch/add', name: 'MTPPitchAdd', component: MTPPitchAdd },
        { path: '/MTPPitch/edit/:id', name: 'MTPPitchEdit', component: MTPPitchEdit },

        { path: '/sector_local_images', name: 'sectorLocalImagesList', component: load('sector_local_images/sectorLocalImagesListComponent') },
        { path: '/sector_local_images/add', name: 'sectorLocalImagesListAdd', component: load('sector_local_images/sectorLocalImageAddComponent') },
        { path: '/sector_local_images/edit/:id', name: 'sectorLocalImagesListEdit', component: load('sector_local_images/sectorLocalImageEditComponent') },

        { path: '/local_bisnes', name: 'localBisnesList', component: load('local_bisnes/localBisnesListComponent') },
        { path: '/local_bisnes/add', name: 'localBisnesAdd', component: load('local_bisnes/localBisnesAddComponent') },
        { path: '/local_bisnes/edit/:id', name: 'localBisnesEdit', component: load('local_bisnes/localBisnesEditComponent') },
        
        { path: '/products_and_categories', name: 'productsList', component: load('products_and_categories/productsAndCatgoriesListComponent') },
        { path: '/product/edit/:id', name: 'productEdit', component: load('products_and_categories/products/productEditComponent') },
        { path: '/product/add', name: 'productAdd', component: load('products_and_categories/products/productAddComponent') },

        { path: '/product_option_control/product_id/:product_id', name: 'productOptionsControl', component: load('products_and_categories/productOptionControlPageComponent') },

        { path: '/productCategory/edit/:id', name: 'productCategoryEdit', component: load('products_and_categories/categories/productCategoryEditComponent'), },
        { path: '/productCategory/add/', name: 'productCategoryAdd', component: load('products_and_categories/categories/productCategoryAddComponent') },

        { path: '/services_list', name: 'servicesList', component: load('services/servicesListComponent'), },
        { path: '/service/edit/:id', name: 'serviceEdit', component: load('services/serviceEditComponent'), },
        { path: '/service/add/', name: 'serviceAdd', component: load('services/serviceAddComponent'), },

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},

        { path: '*', name: 'NotFound', component: NotFound },
    ],
    mode: 'history',
});

router.beforeEach((to, from, next)=>{
    const token = localStorage.getItem('x_xsrf_token')
// console.log(history.back());
    axios
        .get('./api/auth_user')
        .then((response)=>{
            check(to, from, next, token)
        })
        .catch(function (error) {
            // if (error.request.status === 401) {
                if (token != null) {
                    localStorage.removeItem('x_xsrf_token');
                    check(to, from, next, token)
                    return next ({name: 'login'})
                }
                else{
                    check(to, from, next, token)
                }
            // }
        });

    // var token = localStorage.getItem('x_xsrf_token')

    // if (!token) {
    //     if (to.name === 'login' || to.name === 'register') {
    //         return next()
    //     }
    //     else{
    //         return next ({name: 'login'})
    //     }
    // }

    // if (to.name === 'login' || to.name === 'register' && token) {
    //     return next ({name: 'home'})
    // }

    next()
})

function check(to, from, next, token) {

    if (!token) {
        if (to.name == 'login' || to.name == 'register') {
            return next()
        }
        else{
            return next ({name: 'login'})
        }
    }
    else if (token){
        if(to.name === 'login' || to.name === 'register' && token) {
            return next ({name: 'home'})
            // this.$router.go(-1)
            // history.back()
        }
    }
}

export default router
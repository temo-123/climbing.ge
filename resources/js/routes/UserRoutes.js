import VueRouter from 'vue-router'

import homePage from '../components/user/pages/HomePageComponent.vue'
import adminPage from '../components/user/pages/AdminPageComponent.vue'

import myOrderPage from '../components/user/pages/orders/myOrderComponent.vue'
import allOrderPage from '../components/user/pages/orders/allOrderPageComponent.vue'
import decorationeOrder from '../components/user/pages/orders/decorationeOrderPageComponent.vue'

import myPostPage from '../components/user/pages/posts/MyPostsPageCompoents.vue'
import allPostPage from '../components/user/pages/posts/AllPostsPageComponent.vue'

import cartPage from '../components/user/pages/cartPageComponent.vue'
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
import mountList from '../components/user/pages/mountRoutes/mountListComponent.vue'

import articleList from '../components/user/pages/articles/articleListComponent.vue'
import articleEdit from '../components/user/pages/articles/articleEditComponent.vue'
import articleAdd from '../components/user/pages/articles/articleAddComponent.vue'

import spotCategoryEdit from '../components/user/pages/spots_categories/SpotsCategoriesEditPageComponent.vue'
import spotCategoryAdd from '../components/user/pages/spots_categories/SpotsCategoriesAddPageComponent.vue'

import routeAndSectorList from '../components/user/pages/routes_and_sectors/routeAndSectorListComponent.vue'

import routeAdd from '../components/user/pages/routes_and_sectors/routes/routeAddComponent.vue'
import routeEdit from '../components/user/pages/routes_and_sectors/routes/routeEditComponent.vue'

import sectorAdd from '../components/user/pages/routes_and_sectors/sectors/sectorAddComponent.vue'
import sectorEdit from '../components/user/pages/routes_and_sectors/sectors/sectorEditComponent.vue'

import MTPAdd from '../components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue'
import MTPEdit from '../components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue'

import MTPPitchAdd from '../components/user/pages/routes_and_sectors/MTPPitch/MTPPitchAddComponent.vue'
import MTPPitchEdit from '../components/user/pages/routes_and_sectors/MTPPitch/MTPPitchEditComponent.vue'

import productsList from '../components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue'
import productEdit from '../components/user/pages/products_and_categories/products/productEditComponent.vue'
import productAdd from '../components/user/pages/products_and_categories/products/productAddComponent.vue'

import productCategoryEdit from '../components/user/pages/products_and_categories/categories/productCategoryEditComponent.vue'
import productCategoryAdd from '../components/user/pages/products_and_categories/categories/productCategoryAddComponent.vue'

import servicesList from '../components/user/pages/services/servicesListComponent.vue'
import serviceEdit from '../components/user/pages/services/serviceEditComponent.vue'
import serviceAdd from '../components/user/pages/services/serviceAddComponent.vue'

import NotFound from '../components/errors/404Component.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

const router = new VueRouter({
    routes: [
        { path: '/', name: 'home', component: homePage },
        { path: '/admin_page', name: 'adminPage', component: adminPage },

        { path: '/my_orders', name: 'myOrders', component: myOrderPage },
        { path: '/all_orders', name: 'allOrders', component: allOrderPage },
        { path: '/decoratione_order', name: 'decorationeOrder', component: decorationeOrder },

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

        { path: '/site_info', name: 'siteInfo', component: siteInfoPage },
        { path: '/site_info_edit', name: 'siteInfoEdit', component: siteInfoEdit },

        // {
        //     path: `/films`,
        //     component: filmsList,
        //     name: 'filmsList',

        //     children: [
        //         { path: '/add', name: 'filmsAdd', component: filmsAdd },
        //         { path: '/edit/:id', name: 'filmsEdit', component: filmsEdit },
        //     ]
        // },

        { path: '/films', name: 'filmsList', component: filmsList },
        { path: '/films/add', name: 'filmsAdd', component: filmsAdd },
        { path: '/films/edit/:id', name: 'filmsEdit', component: filmsEdit },

        // {
        //     path: `/mount_routes`,
        //     component: mountList,
        //     name: 'mountlist',

        //     children: [
        //         { path: '/edit/:id', name: 'mountRoute/Edit', component: mountRouteEdit },
        //         { path: '/add', name: 'mountRoute/add', component: mountRouteAdd },
        //     ]
        // },

        { path: '/mount_routes/mount_routes_list', name: 'mountlist', component: mountList },
        { path: '/mount_routes/edit/:id', name: 'mountRoute/Edit', component: mountRouteEdit },
        { path: '/mount_routes/add', name: 'mountRoute/add', component: mountRouteAdd },

        // {
        //     path: `/article/:article_category`,
        //     component: articleList,
        //     name: 'articlelist',

        //     children: [
        //         { path: '/edit/:id', name: 'articleEdit', component: articleEdit },
        //         { path: '/add/:article_category', name: 'articleAdd', component: articleAdd },
        //     ]
        // },

        { path: '/article/:article_category', name: 'articlelist', component: articleList },
        { path: '/article/edit/:id', name: 'articleEdit', component: articleEdit },
        { path: '/article/add/:article_category', name: 'articleAdd', component: articleAdd },

        { path: '/spot_category/edit/:id', name: 'spot_category_edit', component: spotCategoryEdit },
        { path: '/spot_category/add/', name: 'spot_category_add', component: spotCategoryAdd },

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

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},

        { path: '*', name: 'NotFound', component: NotFound },
    ],
    mode: 'history',
});

router.beforeEach((to, from, next)=>{
    // const token = localStorage.getItem('x_xsrf_token')
    // var token = '';

    // axios
    // .get('/api/auth_user')
    // .then((response)=>{

    // })
    // .catch(function (error) {
    //     // if (error.request.status === 401) {
    //     //     if (localStorage.getItem("x_xsrf_token") !== null) {
    //     //         localStorage.removeItem('x_xsrf_token');
    //     //         location.reload();
    //     //     }
    //     // }
    // });

    var token = localStorage.getItem('x_xsrf_token')

    if (!token) {
        if (to.name === 'login' || to.name === 'register') {
            return next()
        }
        else{
            return next ({name: 'login'})
        }
    }

    if (to.name === 'login' || to.name === 'register' && token) {
        return next ({name: 'home'})
    }

    next()
})

export default router
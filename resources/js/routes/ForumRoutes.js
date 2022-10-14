import VueRouter from 'vue-router'
import Vue from 'vue'

import forum_index from '../components/forum/pages/ForumListPageComponent.vue'
import about_us from '../components/forum/pages/AboutUsComponent.vue'

import users_list from '../components/forum/pages/UsersListComponent.vue'
import user_page from '../components/forum/pages/UserPageComponent.vue'

import event_list from '../components/forum/pages/EventsListComponent.vue'
import event_page from '../components/forum/pages/EventPageComponent.vue'

import insta_posts from '../components/forum/pages/XXX InstaPostsComponent.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

// function load(component) {
//     return () => import(`@/views/${component}.vue`)
// }

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'forum_index', component: forum_index },

        { path: '/users_list', name: 'users_list', component: users_list },
        { path: '/user/:user_id', name: 'user_page', component: user_page },

        { path: '/events_list', name: 'events_list', component: event_list },
        { path: '/event/:utl_title:', name: 'event_page', component: event_page },

        { path: '/instagram_posts', name: 'insta_posts', component: insta_posts },

        { path: '/about_us', name: 'about_forum', component: about_us,},

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});


// Vue.prototype.$siteData = [];
router.beforeEach((to, from, next)=>{
    localStorage.setItem('lang', 'en')
    // axios
    // .get('/api/auth_user')
    // .then((response)=>{
    //     Vue.prototype.$authUser = response.data
    //     localStorage.setItem('user', 'are login')
    // })
    // .catch(function (error) {
    //     if (error.request.status === 401) {
    //         if (localStorage.getItem("user") !== null) {
    //             localStorage.removeItem('user')
    //             location.reload();
    //         }
    //     }
    // });

    next()
})

export default router
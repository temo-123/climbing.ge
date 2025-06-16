import VueRouter from 'vue-router'
import Vue from 'vue'

import blog_index from '../components/blog/pages/IndexPage.vue'
import about_us from '../components/blog/pages/AboutUsPage.vue'

// import i18n from "../services/localization/i18n";

// function load(component) {
//     return () => import(`../components/site/pages/${component}.vue`);
// }

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'blog_index', component: blog_index },

        { path: '/about_us', name: 'about_blog', component: about_us,},

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
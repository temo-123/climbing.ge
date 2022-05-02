import Vue from 'vue';
require('./bootstrap');

import Carousel3d from 'vue-carousel-3d';
import CKEditor from 'ckeditor4-vue';
import Router from 'vue-router'
import VueMeta from 'vue-meta' // https://www.epiloge.com/how-to-add-dynamic-meta-tags-to-your-vuejs-app-for-google-seo-0fa058
import VueExpandableImage from 'vue-expandable-image'
import Vuex from 'vuex';
import axios from 'axios';

import leftmenu from './components/user/items/LeftMenuComponent.vue'

Vue.component('left-menu', leftmenu)

Vue.use(Vuex);
Vue.use(axios)
Vue.use(VueExpandableImage)
Vue.use(VueMeta)
Vue.use(Carousel3d);
Vue.use(CKEditor);
Vue.use(Router)

// import {Lang} from 'laravel-vue-lang'; // https://www.npmjs.com/package/laravel-vue-lang

// Register the plugin
// var leng = 'ka'
// Vue.use(Lang, {
	// locale: leng,
	// locale: 'ru',
	// fallback: 'ru',
	// ignore: {
	// 	ru: ['validation'],
	// },
// });

import i18n from './i18n'
Vue.use(i18n)
 
import MainWrapper from './components/shop/MainWrapperComponent.vue'
import Index from './components/site/IndexComponent.vue'
import Home from './components/user/HomeComponent.vue'
import Films from './components/films/StudiaComponent.vue'
import Forum from './components/forum/ForumComponent.vue'

import shop_routes from './routes/ShopRoutes';
import site_routes from './routes/SiteRoutes';
import user_routes from './routes/UserRoutes';
import films_routes from './routes/FilmsRoutes';
import forum_routes from './routes/ForumRoutes';
// import auth_routes from './routes/AuthRoutes';

Vue.component('main-wrapper-component',        require('./components/shop/MainWrapperComponent.vue').default);
Vue.component('index-component',               require('./components/site/IndexComponent.vue').default);
Vue.component('home-component',                require('./components/user/HomeComponent.vue').default);
Vue.component('studia-component',              require('./components/films/StudiaComponent.vue').default);
Vue.component('forum-component',              require('./components/forum/ForumComponent.vue').default);


// import router from "./router";
import store from "./store";

Vue.config.productionTip = false;



var serviceRoutes = [];
var homeComponent = [];
if(window.location.hostname == process.env.MIX_SITE_URL){
  homeComponent = Index;
  serviceRoutes = site_routes;
}
else if (window.location.hostname == process.env.MIX_SHOP_URL) {
  homeComponent = MainWrapper;
  serviceRoutes = shop_routes;
} 
else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
  homeComponent = Home;
  serviceRoutes = user_routes;
} 
else if (window.location.hostname == 'films.climbing.loc') {
  homeComponent = Films;
  serviceRoutes = films_routes;
}
else if (window.location.hostname == 'forum.climbing.loc') {
  homeComponent = Forum;
  serviceRoutes = forum_routes;
}else {
  window.location.href = '/404';
}


Vue.prototype.$siteData = [];
const app = new Vue({
    el: '#app',
    i18n,
    store,
    components: {
      homeComponent
    },
    router: serviceRoutes,
    beforeCreate: function () {
      axios
        .get('../api/siteData')
        .then(response => (
          Vue.prototype.$siteData = response.data
        ))
    }

  });
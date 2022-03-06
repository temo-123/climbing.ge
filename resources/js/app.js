import Vue from 'vue';
require('./bootstrap');
// require('./sitemapMiddleware');

import Carousel3d from 'vue-carousel-3d';
import CKEditor from 'ckeditor4-vue';
// import VueNestable from 'vue-nestable'
import Router from 'vue-router'
 
import VueMeta from 'vue-meta' // https://www.epiloge.com/how-to-add-dynamic-meta-tags-to-your-vuejs-app-for-google-seo-0fa058
import VueExpandableImage from 'vue-expandable-image'

// import tabsComponent  from './components/user/items/data_tabs/DataTab/TabsComponent'
// Vue.use(tabsComponent)

Vue.use(VueExpandableImage)
Vue.use(VueMeta)
// Vue.use(VueNestable)
Vue.use(Carousel3d);
Vue.use(CKEditor);
Vue.use(Router)

import {Lang} from 'laravel-vue-lang'; // https://www.npmjs.com/package/laravel-vue-lang

// Register the plugin
Vue.use(Lang, {
	locale: 'en',
	// locale: 'ru',
	// fallback: 'ru',
	// ignore: {
	// 	ru: ['validation'],
	// },
});
 
import MainWrapper from './components/shop/MainWrapperComponent.vue'
import Index from './components/site/IndexComponent.vue'
import Home from './components/user/HomeComponent.vue'
import Films from './components/films/StudiaComponent.vue'

import shop_routes from './routes/ShopRoutes';
import site_routes from './routes/SiteRoutes';
import user_routes from './routes/UserRoutes';
import films_routes from './routes/FilmsRoutes';

Vue.component('main-wrapper-component',        require('./components/shop/MainWrapperComponent.vue').default);
Vue.component('index-component',               require('./components/site/IndexComponent.vue').default);
Vue.component('home-component',                require('./components/user/HomeComponent.vue').default);
Vue.component('studia-component',              require('./components/films/StudiaComponent.vue').default);
 
//  Vue.component('cart_component',                 require('./components/user/CartComponent.vue').default);
//  Vue.component('cont_shopping_component',        require('./components/user/ContShoppingComponent.vue').default);
 
//  Vue.component('gallery_component',              require('./components/user/GalleryComponent.vue').default);
 
//  Vue.component('tabs_component',                 require('./components/user/data_tab/TabsComponent.vue').default);
//  Vue.component('aboutus_tabs_component',         require('./components/user/data_tab/AboutusDataTableComponent.vue').default);
//  Vue.component('comments_list_component',        require('./components/user/CommentsListComponent.vue').default);
//  Vue.component('options_form',                   require('./components/user/OptionsFormComponent.vue').default);
 
//  Vue.component('service_add_form',               require('./components/user/services/ServicesAddFormComponent.vue').default);
//  Vue.component('service_edit_form',              require('./components/user/services/ServicesEditFormComponent.vue').default);
 
//  Vue.component('product_add_form',               require('./components/user/products/ProductAddFormComponent.vue').default);
//  Vue.component('product_edit_form',              require('./components/user/products/ProductEditFormComponent.vue').default);
//  Vue.component('category_add_form',              require('./components/user/products/CategoryAddFormComponent.vue').default);
//  Vue.component('category_edit_form',             require('./components/user/products/CategoryEditFormComponent.vue').default);
 
//  Vue.component('article_add_form',               require('./components/user/articles/ArticleAddFormComponent.vue').default);
//  Vue.component('article_edit_form',              require('./components/user/articles/ArticleEditFormComponent.vue').default);
 
//  Vue.component('sector_add_form',                require('./components/user/routes and sectors/sector/SectorAddFormComponent.vue').default);
//  Vue.component('sector_edit_form',               require('./components/user/routes and sectors/sector/SectorEditFormComponent.vue').default);
 
//  Vue.component('route_add_form',                 require('./components/user/routes and sectors/route/RouteAddFormComponent.vue').default);
//  Vue.component('route_edit_form',                require('./components/user/routes and sectors/route/RouteEditFormComponent.vue').default);
 
//  Vue.component('mtp_add_form',                   require('./components/user/routes and sectors/mtp/MtpAddFormComponent.vue').default);
//  Vue.component('mtp_edit_form',                  require('./components/user/routes and sectors/mtp/MtpEditFormComponent.vue').default);
 
//  Vue.component('mtp_pitch_add_form',             require('./components/user/routes and sectors/mtp_pitch/MtpPitchAddFormComponent.vue').default);
//  Vue.component('mtp_pitch_edit_form',            require('./components/user/routes and sectors/mtp_pitch/MtpPitchEditFormComponent.vue').default);
 
//  Vue.component('mount_add_form',                 require('./components/user/mounts/MountAddFormComponent.vue').default);
//  Vue.component('mount_edit_form',                require('./components/user/mounts/MountEditFormComponent.vue').default);
 
//  Vue.component('site_info_edit_form',            require('./components/user/SiteInfoEditFormComponent.vue').default);
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
}else {
  window.location.href = '/404';
}


Vue.prototype.$siteData = [];
const app = new Vue({
    
    el: '#app',
    components: {homeComponent},
    router: new Router(serviceRoutes),
    beforeCreate: function () {
      axios
        .get('../api/siteData')
        .then(response => (
          Vue.prototype.$siteData = response.data
        ))
    }

});

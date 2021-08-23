/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import Carousel3d from 'vue-carousel-3d';
import CKEditor from 'ckeditor4-vue';
// import ComponentExample from './ComponentExample.vue'
import VueNestable from 'vue-nestable'

Vue.use(VueNestable)


// import VueLazyload from 'vue-lazyload'
// import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
// import CKEditor from 'ckeditor4-vue/dist/legacy.js'

Vue.use( Carousel3d );
Vue.use( CKEditor );

// Vue.use(VueLazyload)
// Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('content');

// import { Carousel3d, Slide } from 'vue-carousel-3d';

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('comment-form-component',         require('./components/site/CommentFormComponent.vue').default);
Vue.component('message-component',              require('./components/site/MessageComponent.vue').default);
Vue.component('search-component',               require('./components/site/SearchComponent.vue').default);
Vue.component('trchtips-component',             require('./components/site/TrchtipsComponent.vue').default);
Vue.component('share-component',                require('./components/site/ShareComponent.vue').default);
Vue.component('gallery-component',              require('./components/site/GalleryComponent.vue').default);
Vue.component('index-gallery-component',        require('./components/site/IndexGalleryComponent.vue').default);
Vue.component('region-routes-component',        require('./components/site/RegionRouteComponent.vue').default);


// Vue.component('product-list-component',        require('./components/shop/ProductListComponent.vue').default); //do't work



Vue.component('tabs_component',                 require('./components/user/TabsComponent.vue').default);
Vue.component('comments_list_component',        require('./components/user/CommentsListComponent.vue').default);
Vue.component('options_form',                   require('./components/user/OptionsFormComponent.vue').default);

Vue.component('service_add_form',               require('./components/user/services/ServicesAddFormComponent.vue').default);
Vue.component('service_edit_form',              require('./components/user/services/ServicesEditFormComponent.vue').default);

Vue.component('product_add_form',               require('./components/user/products/ProductAddFormComponent.vue').default);
Vue.component('product_edit_form',              require('./components/user/products/ProductEditFormComponent.vue').default);
Vue.component('category_add_form',              require('./components/user/products/CategoryAddFormComponent.vue').default);
Vue.component('category_edit_form',             require('./components/user/products/CategoryEditFormComponent.vue').default);

Vue.component('article_add_form',               require('./components/user/articles/ArticleAddFormComponent.vue').default);
Vue.component('article_edit_form',              require('./components/user/articles/ArticleEditFormComponent.vue').default);

Vue.component('sector_add_form',                require('./components/user/routes and sectors/sector/SectorAddFormComponent.vue').default);
Vue.component('sector_edit_form',               require('./components/user/routes and sectors/sector/SectorEditFormComponent.vue').default);

Vue.component('route_add_form',                 require('./components/user/routes and sectors/route/RouteAddFormComponent.vue').default);
Vue.component('route_edit_form',                require('./components/user/routes and sectors/route/RouteEditFormComponent.vue').default);

Vue.component('mtp_add_form',                   require('./components/user/routes and sectors/mtp/MtpAddFormComponent.vue').default);
Vue.component('mtp_edit_form',                  require('./components/user/routes and sectors/mtp/MtpEditFormComponent.vue').default);

Vue.component('mtp_pitch_add_form',             require('./components/user/routes and sectors/mtp_pitch/MtpPitchAddFormComponent.vue').default);
Vue.component('mtp_pitch_edit_form',            require('./components/user/routes and sectors/mtp_pitch/MtpPitchEditFormComponent.vue').default);

Vue.component('mount_add_form',                 require('./components/user/mounts/MountAddFormComponent.vue').default);
Vue.component('mount_edit_form',                require('./components/user/mounts/MountEditFormComponent.vue').default);

Vue.component('site_info_edit_form',            require('./components/user/SiteInfoEditFormComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from 'vue';
import Carousel3d from 'vue-carousel-3d';
import CKEditor from 'ckeditor4-vue';
// import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
// import CKEditor from 'ckeditor4-vue/dist/legacy.js'

Vue.use( Carousel3d );
Vue.use( CKEditor );

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
Vue.component('comment-component',              require('./components/site/CommentsComponent.vue').default);
Vue.component('message-component',              require('./components/site/MessageComponent.vue').default);
Vue.component('search-component',               require('./components/site/SearchComponent.vue').default);
// Vue.component('trchtips_component',             require('./components/site/TrchtipsComponent.vue').default);



Vue.component('tabs_component',                 require('./components/user/TabsComponent.vue').default);
Vue.component('comments_list_component',        require('./components/user/CommentsListComponent.vue').default);
Vue.component('options_form',                   require('./components/user/OptionsFormComponent.vue').default);

Vue.component('article_add_form_component',     require('./components/user/articles/ArticleAddFormComponent.vue').default);
Vue.component('article_edit_form_component',    require('./components/user/articles/ArticleEditFormComponent.vue').default);

Vue.component('route_add_form',                require('./components/user/routes and sectors/route/RouteAddFormComponent.vue').default);
Vue.component('route_edit_form',                require('./components/user/routes and sectors/route/RouteEditFormComponent.vue').default);

Vue.component('mtp_add_form',                require('./components/user/routes and sectors/mtp/MtpAddFormComponent.vue').default);
Vue.component('mtp_edit_form',                require('./components/user/routes and sectors/mtp/MtpEditFormComponent.vue').default);

Vue.component('mtp_pitch_add_form',                require('./components/user/routes and sectors/mtp_pitch/MtpPitchAddFormComponent.vue').default);
Vue.component('mtp_pitch_edit_form',                require('./components/user/routes and sectors/mtp_pitch/MtpPitchEditFormComponent.vue').default);

Vue.component('mount_add_form',     require('./components/user/mounts/MountAddFormComponent.vue').default);
Vue.component('mount_edit_form',     require('./components/user/mounts/MountEditFormComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

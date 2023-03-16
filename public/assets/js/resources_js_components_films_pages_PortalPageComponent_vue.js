(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_films_pages_PortalPageComponent_vue"],{

/***/ "./node_modules/babel-helper-vue-jsx-merge-props/index.js":
/*!****************************************************************!*\
  !*** ./node_modules/babel-helper-vue-jsx-merge-props/index.js ***!
  \****************************************************************/
/***/ ((module) => {

var nestRE = /^(attrs|props|on|nativeOn|class|style|hook)$/

module.exports = function mergeJSXProps (objs) {
  return objs.reduce(function (a, b) {
    var aa, bb, key, nestedKey, temp
    for (key in b) {
      aa = a[key]
      bb = b[key]
      if (aa && nestRE.test(key)) {
        // normalize class
        if (key === 'class') {
          if (typeof aa === 'string') {
            temp = aa
            a[key] = aa = {}
            aa[temp] = true
          }
          if (typeof bb === 'string') {
            temp = bb
            b[key] = bb = {}
            bb[temp] = true
          }
        }
        if (key === 'on' || key === 'nativeOn' || key === 'hook') {
          // merge functions
          for (nestedKey in bb) {
            aa[nestedKey] = mergeFn(aa[nestedKey], bb[nestedKey])
          }
        } else if (Array.isArray(aa)) {
          a[key] = aa.concat(bb)
        } else if (Array.isArray(bb)) {
          a[key] = [aa].concat(bb)
        } else {
          for (nestedKey in bb) {
            aa[nestedKey] = bb[nestedKey]
          }
        }
      } else {
        a[key] = b[key]
      }
    }
    return a
  }, {})
}

function mergeFn (a, b) {
  return function () {
    a && a.apply(this, arguments)
    b && b.apply(this, arguments)
  }
}


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['title', 'description', 'image'],
  data: function data() {
    return {
      locale: localStorage.getItem('lang'),
      mix_site_url: "climbing.loc",
      mix_app_ssh: "https://",
      mix_site_name: "climbing.ge"
    };
  },
  metaInfo: function metaInfo() {
    return {
      title: this.title + ' - ' + 'films.' + this.mix_site_url,
      // title: 'test title',
      htmlAttrs: {
        lang: this.locale
      },
      link: [// { rel: 'stylesheet', href: '/css/index.css' },
      {
        rel: 'canonical',
        href: '/'
      }],
      meta: [{
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      }, {
        charset: 'utf-8'
      }, {
        name: 'description',
        content: this.description
      }, {
        title: 'Default App Title',
        titleTemplate: '%s | ' + this.title + ' - ' + 'films.' + this.mix_site_url
      }, {
        canonical: 'Default App Title',
        content: '%s | ' + this.title + ' - ' + 'films.' + this.mix_site_url
      }, // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + 'films.' + this.mix_site_url
      }, {
        property: 'og:site_name',
        content: this.mix_site_name
      }, // The list of types is available here: http://ogp.me/#types
      {
        property: 'og:type',
        content: 'website'
      }, // Should the the same as your canonical link, see below.
      {
        property: 'og:url',
        content: this.mix_app_ssh + 'films.' + this.mix_site_url
      }, {
        property: 'og:image',
        content: this.image
      }, // Often the same as your meta description, but not always.
      {
        property: 'og:description',
        content: this.description
      }, // Twitter card
      {
        name: 'twitter:card',
        content: 'summary'
      }, {
        name: 'twitter:site',
        content: this.mix_app_ssh + 'films.' + this.mix_site_url
      }, {
        name: 'twitter:title',
        content: this.title + ' - ' + 'films.' + this.mix_site_url
      }, {
        name: 'twitter:description',
        content: this.description
      }, // Your twitter handle, if you have one.
      {
        name: 'twitter:creator',
        content: '@alligatorio'
      }, {
        name: 'twitter:image:src',
        content: this.image
      }, // Google / Schema.org markup:
      {
        itemprop: 'name',
        content: this.title + ' - ' + 'films.' + this.mix_site_url
      }, {
        itemprop: 'description',
        content: this.description
      }, {
        itemprop: 'image',
        content: this.image
      }]
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['film' // 'col'
  ]
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var vue_content_loader__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue-content-loader */ "./node_modules/vue-content-loader/dist/vue-content-loader.es.js");
/* harmony import */ var _items_cards_filmsListCardComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../items/cards/filmsListCardComponent.vue */ "./resources/js/components/films/items/cards/filmsListCardComponent.vue");
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../items/MetaDataComponent */ "./resources/js/components/films/items/MetaDataComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_1__["default"],
    ContentLoader: vue_content_loader__WEBPACK_IMPORTED_MODULE_2__.ContentLoader,
    filmCard: _items_cards_filmsListCardComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      films: [],
      not_filtred_films: [],
      films_loader: true,
      most_liked_film_loader: true,
      day_films: [],
      week_films: [],
      monse_films: [],
      all_time_films: [],
      most_liked_film: [],
      films_categories: [],
      best_film: 1,
      filter_category: 'All'
    };
  },
  mounted: function mounted() {
    this.get_films();
    this.get_films_categories();
    this.get_most_liked_film();
    this.get_user();
  },
  methods: {
    get_films: function get_films() {
      var _this = this;

      axios // .get('../api/film/get_films/'+localStorage.getItem('lang'))
      .get("../api/film/get_films/us").then(function (response) {
        _this.not_filtred_films = response.data;

        _this.sort_film_by_category();
      })["catch"](function (error) {})["finally"](function () {
        return _this.films_loader = false;
      });
    },
    get_films_categories: function get_films_categories() {
      var _this2 = this;

      axios.get("../api/film/get_films_categories/us").then(function (response) {
        _this2.films_categories = response.data;
      })["catch"](function (error) {});
    },
    sort_film_by_category: function sort_film_by_category() {
      var vm = this;

      if (vm.filter_category == 'All') {
        this.films = this.not_filtred_films;
      } else {
        this.films = this.not_filtred_films.filter(function (item) {
          return item.global_film.category_id == vm.filter_category;
        });
      }
    },
    previes_film: function previes_film() {
      if (this.best_film > 1) {
        this.best_film--;
      }
    },
    next_film: function next_film() {
      if (this.best_film < 5) {
        this.best_film++;
      }
    },
    get_user: function get_user() {
      var _this3 = this;

      axios.get('/api/auth_user').then(function (response) {
        _this3.user = response.data['name'];
      })["catch"](this.user = 'Boss');
    },
    // get_best_films_for_day(){
    //     axios
    //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
    //         .get("../api/film/get_films/us")
    //         .then((response) => {
    //             this.day_films = response.data;
    //         })
    //         .catch((error) => {})
    //         .finally(() => (this.films_loading = false));
    // },
    // get_best_films_for_week(){
    //     axios
    //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
    //         .get("../api/film/get_films/us")
    //         .then((response) => {
    //             this.week_films = response.data;
    //         })
    //         .catch((error) => {})
    //         .finally(() => (this.films_loading = false));
    // },
    // get_best_films_for_manse(){
    //     axios
    //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
    //         .get("../api/film/get_films/us")
    //         .then((response) => {
    //             this.monse_films = response.data;
    //         })
    //         .catch((error) => {})
    //         .finally(() => (this.films_loading = false));
    // },
    // get_best_films_for_all_time(){
    //     axios
    //         // .get('../api/film/get_films/'+localStorage.getItem('lang'))
    //         .get("../api/film/get_films/us")
    //         .then((response) => {
    //             this.all_time_films = response.data;
    //         })
    //         .catch((error) => {})
    //         .finally(() => (this.films_loading = false));
    // },
    get_most_liked_film: function get_most_liked_film() {
      var _this4 = this;

      axios.get("../api/film/top_films/get_most_liked_films/us").then(function (response) {
        _this4.most_liked_film = response.data[0];
      })["catch"](function (error) {})["finally"](function () {
        return _this4.most_liked_film_loader = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.film_cover_img img{\n    width: 100%;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.previes_film_bottom{\n  float: left;\n  margin-left: 0.10em;\n}\n.next_film_bottom{\n  float: right;\n  margin-right: -0.10em;\n}\n.previes_film_bottom, .next_film_bottom{\n  cursor: pointer; \n  color: #b3b2b2d9;\n  font-size: 3.5em;\n  margin-top: 4.5em;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./filmsListCardComponent.vue?vue&type=style&index=0&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PortalPageComponent.vue?vue&type=style&index=0&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/vue-content-loader/dist/vue-content-loader.es.js":
/*!***********************************************************************!*\
  !*** ./node_modules/vue-content-loader/dist/vue-content-loader.es.js ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "BulletListLoader": () => (/* binding */ BulletListLoader),
/* harmony export */   "CodeLoader": () => (/* binding */ CodeLoader),
/* harmony export */   "ContentLoader": () => (/* binding */ ContentLoader),
/* harmony export */   "FacebookLoader": () => (/* binding */ FacebookLoader),
/* harmony export */   "InstagramLoader": () => (/* binding */ InstagramLoader),
/* harmony export */   "ListLoader": () => (/* binding */ ListLoader)
/* harmony export */ });
/* harmony import */ var babel_helper_vue_jsx_merge_props__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! babel-helper-vue-jsx-merge-props */ "./node_modules/babel-helper-vue-jsx-merge-props/index.js");
/* harmony import */ var babel_helper_vue_jsx_merge_props__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(babel_helper_vue_jsx_merge_props__WEBPACK_IMPORTED_MODULE_0__);


var uid = (function () {
  return Math.random().toString(36).substring(2);
});

var ContentLoader = {
  name: 'ContentLoader',
  functional: true,
  props: {
    width: {
      type: [Number, String],
      "default": 400
    },
    height: {
      type: [Number, String],
      "default": 130
    },
    speed: {
      type: Number,
      "default": 2
    },
    preserveAspectRatio: {
      type: String,
      "default": 'xMidYMid meet'
    },
    baseUrl: {
      type: String,
      "default": ''
    },
    primaryColor: {
      type: String,
      "default": '#f9f9f9'
    },
    secondaryColor: {
      type: String,
      "default": '#ecebeb'
    },
    primaryOpacity: {
      type: Number,
      "default": 1
    },
    secondaryOpacity: {
      type: Number,
      "default": 1
    },
    uniqueKey: {
      type: String
    },
    animate: {
      type: Boolean,
      "default": true
    }
  },
  render: function render(h, _ref) {
    var props = _ref.props,
        data = _ref.data,
        children = _ref.children;
    var idClip = props.uniqueKey ? props.uniqueKey + "-idClip" : uid();
    var idGradient = props.uniqueKey ? props.uniqueKey + "-idGradient" : uid();
    return h("svg", babel_helper_vue_jsx_merge_props__WEBPACK_IMPORTED_MODULE_0___default()([data, {
      attrs: {
        viewBox: "0 0 " + props.width + " " + props.height,
        version: "1.1",
        preserveAspectRatio: props.preserveAspectRatio
      }
    }]), [h("rect", {
      style: {
        fill: "url(" + props.baseUrl + "#" + idGradient + ")"
      },
      attrs: {
        "clip-path": "url(" + props.baseUrl + "#" + idClip + ")",
        x: "0",
        y: "0",
        width: props.width,
        height: props.height
      }
    }), h("defs", [h("clipPath", {
      attrs: {
        id: idClip
      }
    }, [children || h("rect", {
      attrs: {
        x: "0",
        y: "0",
        rx: "5",
        ry: "5",
        width: props.width,
        height: props.height
      }
    })]), h("linearGradient", {
      attrs: {
        id: idGradient
      }
    }, [h("stop", {
      attrs: {
        offset: "0%",
        "stop-color": props.primaryColor,
        "stop-opacity": props.primaryOpacity
      }
    }, [props.animate ? h("animate", {
      attrs: {
        attributeName: "offset",
        values: "-2; 1",
        dur: props.speed + "s",
        repeatCount: "indefinite"
      }
    }) : null]), h("stop", {
      attrs: {
        offset: "50%",
        "stop-color": props.secondaryColor,
        "stop-opacity": props.secondaryOpacity
      }
    }, [props.animate ? h("animate", {
      attrs: {
        attributeName: "offset",
        values: "-1.5; 1.5",
        dur: props.speed + "s",
        repeatCount: "indefinite"
      }
    }) : null]), h("stop", {
      attrs: {
        offset: "100%",
        "stop-color": props.primaryColor,
        "stop-opacity": props.primaryOpacity
      }
    }, [props.animate ? h("animate", {
      attrs: {
        attributeName: "offset",
        values: "-1; 2",
        dur: props.speed + "s",
        repeatCount: "indefinite"
      }
    }) : null])])])]);
  }
};

var BulletListLoader = {
  name: 'BulletListLoader',
  functional: true,
  render: function render(h, _ref) {
    var data = _ref.data;
    return h(ContentLoader, data, [h("circle", {
      attrs: {
        cx: "10",
        cy: "20",
        r: "8"
      }
    }), h("rect", {
      attrs: {
        x: "25",
        y: "15",
        rx: "5",
        ry: "5",
        width: "220",
        height: "10"
      }
    }), h("circle", {
      attrs: {
        cx: "10",
        cy: "50",
        r: "8"
      }
    }), h("rect", {
      attrs: {
        x: "25",
        y: "45",
        rx: "5",
        ry: "5",
        width: "220",
        height: "10"
      }
    }), h("circle", {
      attrs: {
        cx: "10",
        cy: "80",
        r: "8"
      }
    }), h("rect", {
      attrs: {
        x: "25",
        y: "75",
        rx: "5",
        ry: "5",
        width: "220",
        height: "10"
      }
    }), h("circle", {
      attrs: {
        cx: "10",
        cy: "110",
        r: "8"
      }
    }), h("rect", {
      attrs: {
        x: "25",
        y: "105",
        rx: "5",
        ry: "5",
        width: "220",
        height: "10"
      }
    })]);
  }
};

var CodeLoader = {
  name: 'CodeLoader',
  functional: true,
  render: function render(h, _ref) {
    var data = _ref.data;
    return h(ContentLoader, data, [h("rect", {
      attrs: {
        x: "0",
        y: "0",
        rx: "3",
        ry: "3",
        width: "70",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "80",
        y: "0",
        rx: "3",
        ry: "3",
        width: "100",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "190",
        y: "0",
        rx: "3",
        ry: "3",
        width: "10",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "15",
        y: "20",
        rx: "3",
        ry: "3",
        width: "130",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "155",
        y: "20",
        rx: "3",
        ry: "3",
        width: "130",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "15",
        y: "40",
        rx: "3",
        ry: "3",
        width: "90",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "115",
        y: "40",
        rx: "3",
        ry: "3",
        width: "60",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "185",
        y: "40",
        rx: "3",
        ry: "3",
        width: "60",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "60",
        rx: "3",
        ry: "3",
        width: "30",
        height: "10"
      }
    })]);
  }
};

var FacebookLoader = {
  name: 'FacebookLoader',
  functional: true,
  render: function render(h, _ref) {
    var data = _ref.data;
    return h(ContentLoader, data, [h("rect", {
      attrs: {
        x: "70",
        y: "15",
        rx: "4",
        ry: "4",
        width: "117",
        height: "6.4"
      }
    }), h("rect", {
      attrs: {
        x: "70",
        y: "35",
        rx: "3",
        ry: "3",
        width: "85",
        height: "6.4"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "80",
        rx: "3",
        ry: "3",
        width: "350",
        height: "6.4"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "100",
        rx: "3",
        ry: "3",
        width: "380",
        height: "6.4"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "120",
        rx: "3",
        ry: "3",
        width: "201",
        height: "6.4"
      }
    }), h("circle", {
      attrs: {
        cx: "30",
        cy: "30",
        r: "30"
      }
    })]);
  }
};

var ListLoader = {
  name: 'ListLoader',
  functional: true,
  render: function render(h, _ref) {
    var data = _ref.data;
    return h(ContentLoader, data, [h("rect", {
      attrs: {
        x: "0",
        y: "0",
        rx: "3",
        ry: "3",
        width: "250",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "20",
        y: "20",
        rx: "3",
        ry: "3",
        width: "220",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "20",
        y: "40",
        rx: "3",
        ry: "3",
        width: "170",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "60",
        rx: "3",
        ry: "3",
        width: "250",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "20",
        y: "80",
        rx: "3",
        ry: "3",
        width: "200",
        height: "10"
      }
    }), h("rect", {
      attrs: {
        x: "20",
        y: "100",
        rx: "3",
        ry: "3",
        width: "80",
        height: "10"
      }
    })]);
  }
};

var InstagramLoader = {
  name: 'InstagramLoader',
  functional: true,
  render: function render(h, _ref) {
    var data = _ref.data;
    return h(ContentLoader, babel_helper_vue_jsx_merge_props__WEBPACK_IMPORTED_MODULE_0___default()([data, {
      attrs: {
        height: 480
      }
    }]), [h("circle", {
      attrs: {
        cx: "30",
        cy: "30",
        r: "30"
      }
    }), h("rect", {
      attrs: {
        x: "75",
        y: "13",
        rx: "4",
        ry: "4",
        width: "100",
        height: "13"
      }
    }), h("rect", {
      attrs: {
        x: "75",
        y: "37",
        rx: "4",
        ry: "4",
        width: "50",
        height: "8"
      }
    }), h("rect", {
      attrs: {
        x: "0",
        y: "70",
        rx: "5",
        ry: "5",
        width: "400",
        height: "400"
      }
    })]);
  }
};




/***/ }),

/***/ "./resources/js/components/films/items/MetaDataComponent.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/films/items/MetaDataComponent.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=224e3538& */ "./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/films/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/films/items/cards/filmsListCardComponent.vue":
/*!******************************************************************************!*\
  !*** ./resources/js/components/films/items/cards/filmsListCardComponent.vue ***!
  \******************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./filmsListCardComponent.vue?vue&type=template&id=2cf02ea6& */ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6&");
/* harmony import */ var _filmsListCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./filmsListCardComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _filmsListCardComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./filmsListCardComponent.vue?vue&type=style&index=0&lang=css& */ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _filmsListCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__.render,
  _filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/films/items/cards/filmsListCardComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/films/pages/PortalPageComponent.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/components/films/pages/PortalPageComponent.vue ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PortalPageComponent.vue?vue&type=template&id=63f8b39c& */ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c&");
/* harmony import */ var _PortalPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PortalPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _PortalPageComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./PortalPageComponent.vue?vue&type=style&index=0&lang=css& */ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _PortalPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__.render,
  _PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/films/pages/PortalPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./filmsListCardComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PortalPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./filmsListCardComponent.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=style&index=0&lang=css&");


/***/ }),

/***/ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_style_index_0_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PortalPageComponent.vue?vue&type=style&index=0&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=style&index=0&lang=css&");


/***/ }),

/***/ "./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_224e3538___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=224e3538& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538&");


/***/ }),

/***/ "./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_filmsListCardComponent_vue_vue_type_template_id_2cf02ea6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./filmsListCardComponent.vue?vue&type=template&id=2cf02ea6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6&");


/***/ }),

/***/ "./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_PortalPageComponent_vue_vue_type_template_id_63f8b39c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PortalPageComponent.vue?vue&type=template&id=63f8b39c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/MetaDataComponent.vue?vue&type=template&id=224e3538& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div")
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/items/cards/filmsListCardComponent.vue?vue&type=template&id=2cf02ea6& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { class: "mb-5 col-xs-6 col-sm-6 col-md-4" }, [
    _c("div", { staticClass: "card h-100" }, [
      _c("div", { staticClass: "card-body" }, [
        _c("div", { staticClass: "row" }, [
          _c(
            "div",
            { staticClass: "col-md-12 film_cover_img" },
            [
              _c(
                "router-link",
                { attrs: { to: "../film/" + _vm.film.global_film.url_title } },
                [
                  !_vm.film.global_film.image
                    ? _c("site-img", {
                        attrs: {
                          src: "../../../public/images/site_img/film poster demo.jpg",
                          img_class:
                            "img-fluid rounded mb-4 mb-lg-0 float-right",
                          alt: _vm.film.local_film.name,
                        },
                      })
                    : _c("site-img", {
                        attrs: {
                          src:
                            "../../../public/images/film_img/" +
                            _vm.film.global_film.image,
                          img_class:
                            "img-fluid rounded mb-4 mb-lg-0 float-right",
                          alt: _vm.film.local_film.name,
                        },
                      }),
                ],
                1
              ),
            ],
            1
          ),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "row" }, [
          _c(
            "div",
            { staticClass: "col-md-12" },
            [
              _c(
                "router-link",
                { attrs: { to: "../film/" + _vm.film.global_film.url_title } },
                [
                  _c("h2", { staticClass: "card-title" }, [
                    _vm._v(_vm._s(_vm.film.local_film.name)),
                  ]),
                ]
              ),
              _vm._v(" "),
              _c("p", { staticClass: "card-text" }, [
                _vm._v(_vm._s(_vm.film.local_film.short_description)),
              ]),
            ],
            1
          ),
        ]),
      ]),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/films/pages/PortalPageComponent.vue?vue&type=template&id=63f8b39c& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "container px-4 px-lg-5" },
    [
      _c("div", { staticClass: "row gx-4 gx-lg-5 align-items-center my-5" }, [
        _c("div", { staticClass: "container" }, [
          _vm.most_liked_film_loader
            ? _c(
                "div",
                { staticClass: "row" },
                [
                  _c("content-loader", {
                    attrs: {
                      viewBox: "0 0",
                      primaryColor: "#f3f3f3",
                      secondaryColor: "#777777",
                    },
                  }),
                ],
                1
              )
            : _c("div", { staticClass: "row" }, [
                _c("h2", { staticClass: "text-center" }, [
                  _vm._v("Most liked film"),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c(
                    "div",
                    { staticClass: "col-xs-12 col-sm-12 col-md-6 col-lg-6" },
                    [
                      !_vm.most_liked_film.global_film.image
                        ? _c("site-img", {
                            attrs: {
                              src: "../../../public/images/site_img/film poster demo.jpg",
                              img_class:
                                "img-fluid rounded mb-4 mb-lg-0 float-right",
                              alt: _vm.most_liked_film.local_film.name,
                            },
                          })
                        : _c("site-img", {
                            attrs: {
                              src:
                                "../../../public/images/film_img/" +
                                _vm.most_liked_film.global_film.image,
                              img_class:
                                "img-fluid rounded mb-4 mb-lg-0 float-right",
                              alt: _vm.most_liked_film.local_film.name,
                            },
                          }),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "col-xs-12 col-sm-12 col-md-6 col-lg-6" },
                    [
                      _c(
                        "div",
                        { staticClass: "row" },
                        [
                          _c(
                            "router-link",
                            {
                              attrs: {
                                to:
                                  "film/" +
                                  _vm.most_liked_film.global_film.url_title,
                              },
                            },
                            [
                              _c("h1", { staticClass: "font-weight-light" }, [
                                _vm._v(
                                  _vm._s(_vm.most_liked_film.local_film.name)
                                ),
                              ]),
                            ]
                          ),
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _vm.most_liked_film.local_film.coutry ||
                      _vm.most_liked_film.local_film.issue_year
                        ? _c("div", { staticClass: "row" }, [
                            _vm.most_liked_film.local_film.coutry
                              ? _c("p", [
                                  _vm._v(
                                    "Coutry - " +
                                      _vm._s(
                                        _vm.most_liked_film.local_film.coutry
                                      )
                                  ),
                                ])
                              : _vm._e(),
                            _vm._v(" "),
                            _vm.most_liked_film.local_film.issue_year
                              ? _c("p", [
                                  _vm._v(
                                    "Year of issue - " +
                                      _vm._s(
                                        _vm.most_liked_film.local_film
                                          .issue_year
                                      )
                                  ),
                                ])
                              : _vm._e(),
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _c("div", { staticClass: "row" }, [
                        _vm._v(
                          "\n                            " +
                            _vm._s(
                              _vm.most_liked_film.local_film.short_description
                            ) +
                            "\n                        "
                        ),
                      ]),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "row" },
                        [
                          _c(
                            "router-link",
                            {
                              attrs: {
                                to:
                                  "film/" +
                                  _vm.most_liked_film.global_film.url_title,
                              },
                            },
                            [
                              _c("button", { staticClass: "btn btn-primary" }, [
                                _vm._v("Open"),
                              ]),
                            ]
                          ),
                        ],
                        1
                      ),
                    ]
                  ),
                ]),
              ]),
        ]),
      ]),
      _vm._v(" "),
      _vm._m(0),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "container articles_filter_bar" }, [
          _c("div", { staticClass: "col-md-6 col-sm-6" }, [
            _vm._v(
              "\n                Select category and filtred films by category\n            "
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "col-md-6 col-sm-6" }, [
            _c(
              "select",
              {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.filter_category,
                    expression: "filter_category",
                  },
                ],
                staticClass: "form-control",
                on: {
                  click: function ($event) {
                    return _vm.sort_film_by_category()
                  },
                  change: function ($event) {
                    var $$selectedVal = Array.prototype.filter
                      .call($event.target.options, function (o) {
                        return o.selected
                      })
                      .map(function (o) {
                        var val = "_value" in o ? o._value : o.value
                        return val
                      })
                    _vm.filter_category = $event.target.multiple
                      ? $$selectedVal
                      : $$selectedVal[0]
                  },
                },
              },
              [
                _c("option", [_vm._v("All")]),
                _vm._v(" "),
                _vm._l(_vm.films_categories, function (category) {
                  return _c(
                    "option",
                    { key: category.id, domProps: { value: category.id } },
                    [_vm._v(_vm._s(category.us_name))]
                  )
                }),
              ],
              2
            ),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row gx-4 gx-lg-5" }, [
        _vm.films_loader
          ? _c(
              "div",
              [
                _c("content-loader", {
                  attrs: {
                    viewBox: "0 0",
                    primaryColor: "#f3f3f3",
                    secondaryColor: "#777777",
                  },
                }),
              ],
              1
            )
          : _c("div", [
              _c(
                "div",
                { staticClass: "row" },
                _vm._l(_vm.films, function (film) {
                  return _c("filmCard", {
                    key: film.global_film.id,
                    attrs: { film: film },
                  })
                }),
                1
              ),
            ]),
      ]),
      _vm._v(" "),
      _c("metaData", {
        attrs: {
          title: "Climbing films",
          description:
            "Rock climbing, mountaineering and other outdoor actyvity films",
          image: "../../../../public/images/meta_img/films.jpg",
        },
      }),
    ],
    1
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "card text-white bg-secondary my-5 py-4 text-center" },
      [
        _c("div", { staticClass: "card-body" }, [
          _c("h2", { staticClass: "text-white m-0" }, [_vm._v("All films")]),
        ]),
      ]
    )
  },
]
render._withStripped = true



/***/ })

}]);
"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_site_pages_IndexPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _cards_EventCardComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./cards/EventCardComponent */ "./resources/js/components/site/items/cards/EventCardComponent.vue");
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
  data: function data() {
    return {
      events: []
    };
  },
  components: {
    eventCard: _cards_EventCardComponent__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  mounted: function mounted() {
    this.get_event();
  },
  watch: {
    '$route': function $route(to, from) {
      this.get_event();
    }
  },
  methods: {
    get_event: function get_event() {
      var _this = this;

      axios.get('../api/event/get_event_on_site_list/' + localStorage.getItem('lang')).then(function (response) {
        _this.events = response.data;
      })["catch"](function (error) {});
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['title', 'description', 'image'],
  data: function data() {
    return {
      locale: localStorage.getItem('lang'),
      mix_site_url: "climbing.loc",
      mix_app_ssh: "http://",
      mix_site_name: "climbing.ge"
    };
  },
  metaInfo: function metaInfo() {
    return {
      title: this.title + ' - ' + this.mix_site_url,
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
        titleTemplate: '%s | ' + this.title + ' - ' + this.mix_site_url
      }, {
        canonical: 'Default App Title',
        content: '%s | ' + this.title + ' - ' + this.mix_site_url
      }, // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + this.mix_site_url
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
        content: this.mix_app_ssh + this.mix_site_url
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
        content: this.mix_app_ssh + this.mix_site_url
      }, {
        name: 'twitter:title',
        content: this.title + ' - ' + this.mix_site_url
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
        content: this.title + ' - ' + this.mix_site_url
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _cards_ArticleCardComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./cards/ArticleCardComponent */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

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
    articleCardComponent: _cards_ArticleCardComponent__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  mounted: function mounted() {
    this.get_other_articles();
  },
  watch: {
    '$route': function $route(to, from) {
      this.get_other_articles();
    }
  },
  methods: {
    get_other_articles: function get_other_articles() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                axios.get('../api/articles/other/' + localStorage.getItem('lang')).then(function (response) {
                  _this.other_articles = response.data;
                })["catch"](function (error) {});

              case 1:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      slides: [{
        id: '1',
        title: 'Slide #1',
        text: 'Slide text 1',
        content: 'Slide 1 content.',
        image: '../images/gallery_img/2021-11-26-20-11-02.jpg',
        slide_text_position: 1
      } // {   
      //     id: '2',
      //     title: 'Slide #2',
      //     text: 'Slide text 2',
      //     content: 'Slide 2 content.',
      //     image: '../images/gallery_img/IMG_20220209_182522.jpg',
      //     slide_text_position: 4
      // }
      ],
      carrent_slider_index: 0
    };
  },
  methods: {
    next_image: function next_image() {
      this.carrent_slider_index++;
    },
    previes_image: function previes_image() {
      if (this.carrent_slider_index > 0) {
        this.carrent_slider_index--;
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
  data: function data() {
    return {
      techtips: [],
      slider_index: 0 // tip_num: 0

    };
  },
  mounted: function mounted() {
    this.get_techtips();
  },
  methods: {
    get_techtips: function get_techtips() {
      var _this = this;

      axios.get('../api/articles/tech_tip/' + localStorage.getItem('lang')).then(function (response) {
        _this.techtips = response.data;
      })["catch"](function (error) {});
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['article', 'image_dir', 'route'],
  data: function data() {
    return {};
  },
  components: {},
  mounted: function mounted() {// console.log(this.article);
    // console.log(this.image_dir);
    // console.log(this.route);
  },
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['news'],
  data: function data() {
    return {};
  },
  components: {},
  mounted: function mounted() {},
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['news'],
  data: function data() {
    return {};
  },
  components: {},
  mounted: function mounted() {},
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
// https://vuejsexamples.com/vuejs-responsive-and-customizable-image-and-video-gallery-2/
// import VueGallery from 'vue-gallery';
// const VueGallery = require('vue-gallery');
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  // components: {
  //   'gallery': VueGallery
  // },
  props: ['article_id' // 'image_url',
  ],
  data: function data() {
    return {
      db_images: [],
      active_img: [],
      open_img: false
    };
  },
  mounted: function mounted() {
    this.get_index_gallery_image();
  },
  methods: {
    get_index_gallery_image: function get_index_gallery_image() {
      var _this = this;

      axios.get('../api/gallery_image/', {}).then(function (response) {
        _this.db_images = response.data;
      })["catch"](function (error) {});
    },
    open_image: function open_image(db_img) {
      this.active_img = db_img;
      this.open_img = true;
      document.body.classList.add('body_hiden'); // off page scroling
    },
    close_image: function close_image() {
      this.active_img = [];
      this.open_img = false;
      document.body.classList.remove('body_hiden'); // on page scroling
    },
    previes_image: function previes_image() {
      console.log('previes');
    },
    next_image: function next_image() {
      console.log('next');
    }
  } // created(){
  //       axios
  //         .get('../api/gallery_image', {
  //         })
  //         .then(response => {
  //           // console.log('response.data');
  //           // console.log(response.data);
  //           this.db_images = response.data
  //         })
  //         .catch(error =>{
  //         })
  // }

});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _items_galleries_IndexGalleryComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../items/galleries/IndexGalleryComponent */ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue");
/* harmony import */ var _items_TechtipsComponent__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../items/TechtipsComponent */ "./resources/js/components/site/items/TechtipsComponent.vue");
/* harmony import */ var _items_OtherArticlesComponent__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../items/OtherArticlesComponent */ "./resources/js/components/site/items/OtherArticlesComponent.vue");
/* harmony import */ var _items_EventComponent__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../items/EventComponent */ "./resources/js/components/site/items/EventComponent.vue");
/* harmony import */ var _items_SwiperComponent__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ../items/SwiperComponent */ "./resources/js/components/site/items/SwiperComponent.vue");
/* harmony import */ var _items_WhatWeDoComponent__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ../items/WhatWeDoComponent */ "./resources/js/components/site/items/WhatWeDoComponent.vue");
/* harmony import */ var _items_cards_NewsCardComponent__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ../items/cards/NewsCardComponent */ "./resources/js/components/site/items/cards/NewsCardComponent.vue");
/* harmony import */ var _items_cards_BigNewsCardComponent__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ../items/cards/BigNewsCardComponent */ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue");
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ../items/MetaDataComponent */ "./resources/js/components/site/items/MetaDataComponent.vue");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

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
  data: function data() {
    return {
      newses: [],
      lastNews: []
    };
  },
  components: {
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_9__["default"],
    indexGalleryComponent: _items_galleries_IndexGalleryComponent__WEBPACK_IMPORTED_MODULE_1__["default"],
    techtipsComponent: _items_TechtipsComponent__WEBPACK_IMPORTED_MODULE_2__["default"],
    otherArticlesComponent: _items_OtherArticlesComponent__WEBPACK_IMPORTED_MODULE_3__["default"],
    eventComponent: _items_EventComponent__WEBPACK_IMPORTED_MODULE_4__["default"],
    swiperComponent: _items_SwiperComponent__WEBPACK_IMPORTED_MODULE_5__["default"],
    newsCard: _items_cards_NewsCardComponent__WEBPACK_IMPORTED_MODULE_7__["default"],
    bigNewsCard: _items_cards_BigNewsCardComponent__WEBPACK_IMPORTED_MODULE_8__["default"],
    whatWeDoComponent: _items_WhatWeDoComponent__WEBPACK_IMPORTED_MODULE_6__["default"]
  },
  mounted: function mounted() {
    this.get_news(); // this.get_last_news()
  },
  watch: {
    '$route': function $route(to, from) {
      this.get_news(); // this.get_last_news()

      window.scrollTo(0, 0);
    }
  },
  methods: {
    get_news: function get_news() {
      var _this = this;

      return _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().mark(function _callee() {
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default().wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                axios.get('../api/articles/news/' + localStorage.getItem('lang')).then(function (response) {
                  _this.newses = response.data;
                  _this.lastNews = response.data[0]; // this.get_news()
                })["catch"](function (error) {});

              case 1:
              case "end":
                return _context.stop();
            }
          }
        }, _callee);
      }))();
    } // async get_last_news(){
    //     axios
    //     .get('../api/last_news/'+localStorage.getItem('lang'))
    //     .then(response => {
    //         this.lastNews = response.data
    //     })
    //     .catch(error =>{
    //     })
    // }

  }
});

/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.head_slider[data-v-7794a48e]{\n  /* white-space: normal;\n  background-size: cover;\n  flex-shrink: 0;\n  display: block;\n  width: 100%;\n  position: relative; */\n}\n.head_slide[data-v-7794a48e]{\n}\n.swiper[data-v-7794a48e]{\n  max-width: 100%; \n  overflow: hidden;\n}\n.swiper_sizing[data-v-7794a48e]{\n  /* display: flex; */\n}\n.previes_slide_bottom[data-v-7794a48e]{\n    left: 1%;\n}\n.next_slide_bottom[data-v-7794a48e]{\n    right: 1%;\n}\n.next_slide_bottom[data-v-7794a48e], .previes_slide_bottom[data-v-7794a48e]{\n    margin-top: -18%;\n    font-size: 300%;\n    cursor: pointer;\n    text-shadow: #d0d0d0 1px 1px 1px;\n    position: absolute;\n}\n.slider_img[data-v-7794a48e]{\n  width: 100%\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n@media (max-width: 990px){\n.thumbnail[data-v-2cc95823]{\n        height: 18em;\n        max-height: 22em;\n        margin-top: 20px;\n}\n.thumbnail p[data-v-2cc95823]{\n        font-size: 80%;\n}\n}\n@media (max-width: 375px){\n.thumbnail[data-v-2cc95823]{\n        height: 9em;\n        max-height: 22em;\n        margin-top: 16px;\n}\n.thumbnail p[data-v-2cc95823]{\n        font-size: 80%;\n}\n}\n.tip[data-v-2cc95823]{\n    width: 25%;\n    height: auto;\n    margin: 2%;\n}\n.caption h3[data-v-2cc95823]{\n    margin: 0;\n}\n.caption p[data-v-2cc95823]{\n    text-align: center;\n}\n/* .tips_list{\n    max-height: 100%; \n    overflow: hidden;\n}\n.tips{\n    display: flex;\n} */\n.previes_tip_bottom[data-v-2cc95823]{\n    float: left;\n}\n.next_tip_bottom[data-v-2cc95823]{\n    float: right;\n}\n.next_tip_bottom[data-v-2cc95823], .previes_tip_bottom[data-v-2cc95823]{\n    margin-top: -12%;\n    font-size: 150%;\n    cursor: pointer;\n}\n.discount_percent_badge_for_techtip[data-v-2cc95823] {\n    margin: 5px 20px 0 0;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.close_bottom[data-v-7d670332]{\n  float: right;\n  cursor: pointer; \n  color: #b3b2b2d9;\n  font-size: 2em;\n  margin-right: 0.4em;\n  margin-top: 0.4em;\n}\n.gallery_img[data-v-7d670332]{\n  max-width: 100%;\n}\n.gallery_images[data-v-7d670332]{\n  cursor: pointer;\n}\n/* .image_moving{\n\n} */\n.previes_img_bottom[data-v-7d670332]{\n  float: left;\n  margin-left: 0.1em;\n}\n.next_img_bottom[data-v-7d670332]{\n  float: right;\n  margin-right: -0.6em;\n}\n.previes_img_bottom[data-v-7d670332], .next_img_bottom[data-v-7d670332]{\n  cursor: pointer; \n  color: #b3b2b2d9;\n  font-size: 3.5em;\n  margin-top: 7em;\n}\n.open_img[data-v-7d670332]{\n    position: fixed;\n    top: 0;\n    right: 0;\n    bottom: 0;\n    left: 0;\n    z-index: 1050;\n    background: #000000d9;\n\n    transition: opacity .15s linear;\n}\n.gallery_big_img[data-v-7d670332]{\n  position: relative;\n  top: 5%;\n  width: 80%;\n  max-height: 90%;\n  display: block;\n  margin-left: auto;\n  margin-right: auto;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_style_index_0_id_7794a48e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_style_index_0_id_7794a48e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_style_index_0_id_7794a48e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_style_index_0_id_2cc95823_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_style_index_0_id_2cc95823_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_style_index_0_id_2cc95823_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_style_index_0_id_7d670332_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_style_index_0_id_7d670332_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_style_index_0_id_7d670332_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/site/items/EventComponent.vue":
/*!***************************************************************!*\
  !*** ./resources/js/components/site/items/EventComponent.vue ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EventComponent.vue?vue&type=template&id=229b0242& */ "./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242&");
/* harmony import */ var _EventComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EventComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EventComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__.render,
  _EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/EventComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");
var render, staticRenderFns
;



/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"],
  render,
  staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/OtherArticlesComponent.vue":
/*!***********************************************************************!*\
  !*** ./resources/js/components/site/items/OtherArticlesComponent.vue ***!
  \***********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OtherArticlesComponent.vue?vue&type=template&id=5de136ec& */ "./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec&");
/* harmony import */ var _OtherArticlesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OtherArticlesComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _OtherArticlesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__.render,
  _OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/OtherArticlesComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/SwiperComponent.vue":
/*!****************************************************************!*\
  !*** ./resources/js/components/site/items/SwiperComponent.vue ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true& */ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true&");
/* harmony import */ var _SwiperComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SwiperComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _SwiperComponent_vue_vue_type_style_index_0_id_7794a48e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& */ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _SwiperComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7794a48e",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/SwiperComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/TechtipsComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/site/items/TechtipsComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true& */ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true&");
/* harmony import */ var _TechtipsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./TechtipsComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _TechtipsComponent_vue_vue_type_style_index_0_id_2cc95823_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& */ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _TechtipsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "2cc95823",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/TechtipsComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/WhatWeDoComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/site/items/WhatWeDoComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./WhatWeDoComponent.vue?vue&type=template&id=02488a74& */ "./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74&");
/* harmony import */ var _WhatWeDoComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./WhatWeDoComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _WhatWeDoComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__.render,
  _WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/WhatWeDoComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ArticleCardComponent.vue?vue&type=template&id=5d4536ff& */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&");
/* harmony import */ var _ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ArticleCardComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.render,
  _ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/cards/ArticleCardComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/BigNewsCardComponent.vue ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc& */ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc&");
/* harmony import */ var _BigNewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BigNewsCardComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _BigNewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__.render,
  _BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/cards/BigNewsCardComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/cards/NewsCardComponent.vue":
/*!************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/NewsCardComponent.vue ***!
  \************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NewsCardComponent.vue?vue&type=template&id=4c387c3c& */ "./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c&");
/* harmony import */ var _NewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NewsCardComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__.render,
  _NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/cards/NewsCardComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue":
/*!********************************************************************************!*\
  !*** ./resources/js/components/site/items/galleries/IndexGalleryComponent.vue ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true& */ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true&");
/* harmony import */ var _IndexGalleryComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IndexGalleryComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _IndexGalleryComponent_vue_vue_type_style_index_0_id_7d670332_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& */ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _IndexGalleryComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "7d670332",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/items/galleries/IndexGalleryComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/pages/IndexPageComponent.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/site/pages/IndexPageComponent.vue ***!
  \*******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IndexPageComponent.vue?vue&type=template&id=2de6ebd4& */ "./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4&");
/* harmony import */ var _IndexPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IndexPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IndexPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__.render,
  _IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/pages/IndexPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EventComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EventComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EventComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OtherArticlesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OtherArticlesComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OtherArticlesComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SwiperComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TechtipsComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WhatWeDoComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WhatWeDoComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WhatWeDoComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleCardComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BigNewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./BigNewsCardComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BigNewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NewsCardComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NewsCardComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexGalleryComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& ***!
  \*************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_style_index_0_id_7794a48e_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=style&index=0&id=7794a48e&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_style_index_0_id_2cc95823_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=style&index=0&id=2cc95823&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************!*\
  !*** ./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_0_rules_0_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_style_index_0_id_7d670332_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/style-loader/dist/cjs.js!../../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!../../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9[0].rules[0].use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=style&index=0&id=7d670332&scoped=true&lang=css&");


/***/ }),

/***/ "./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EventComponent_vue_vue_type_template_id_229b0242___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EventComponent.vue?vue&type=template&id=229b0242& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242&");


/***/ }),

/***/ "./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OtherArticlesComponent_vue_vue_type_template_id_5de136ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./OtherArticlesComponent.vue?vue&type=template&id=5de136ec& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec&");


/***/ }),

/***/ "./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true&":
/*!***********************************************************************************************************!*\
  !*** ./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true& ***!
  \***********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SwiperComponent_vue_vue_type_template_id_7794a48e_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true&");


/***/ }),

/***/ "./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_TechtipsComponent_vue_vue_type_template_id_2cc95823_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true&");


/***/ }),

/***/ "./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_WhatWeDoComponent_vue_vue_type_template_id_02488a74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WhatWeDoComponent.vue?vue&type=template&id=02488a74& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74&");


/***/ }),

/***/ "./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ArticleCardComponent_vue_vue_type_template_id_5d4536ff___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ArticleCardComponent.vue?vue&type=template&id=5d4536ff& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&");


/***/ }),

/***/ "./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BigNewsCardComponent_vue_vue_type_template_id_0ba77cbc___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc&");


/***/ }),

/***/ "./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c&":
/*!*******************************************************************************************************!*\
  !*** ./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c& ***!
  \*******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NewsCardComponent_vue_vue_type_template_id_4c387c3c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./NewsCardComponent.vue?vue&type=template&id=4c387c3c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c&");


/***/ }),

/***/ "./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true&":
/*!***************************************************************************************************************************!*\
  !*** ./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true& ***!
  \***************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexGalleryComponent_vue_vue_type_template_id_7d670332_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true&");


/***/ }),

/***/ "./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4&":
/*!**************************************************************************************************!*\
  !*** ./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4& ***!
  \**************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPageComponent_vue_vue_type_template_id_2de6ebd4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./IndexPageComponent.vue?vue&type=template&id=2de6ebd4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/EventComponent.vue?vue&type=template&id=229b0242& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return this.events.length > 0
    ? _c(
        "div",
        {
          staticClass: "container h-recent-work events",
          attrs: { id: "event" },
        },
        [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "container" }, [
              _c("h2", { staticClass: "index_h2" }, [
                _vm._v(_vm._s(_vm.$t("title events"))),
              ]),
              _vm._v(" "),
              _vm._m(0),
              _vm._v(" "),
              _c("h3", [
                _c("span", {
                  domProps: {
                    innerHTML: _vm._s(this.$siteData.event_description),
                  },
                }),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "container" }, [
                _c(
                  "div",
                  { staticClass: "row event-list" },
                  _vm._l(_vm.events, function (event) {
                    return _c("eventCard", {
                      key: event.id,
                      attrs: { event: event },
                    })
                  }),
                  1
                ),
              ]),
            ]),
          ]),
        ]
      )
    : _vm._e()
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-calendar" }),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/OtherArticlesComponent.vue?vue&type=template&id=5de136ec& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return this.other_articles.length > 0
    ? _c("div", { staticClass: "h-recent-work", attrs: { id: "other" } }, [
        _c(
          "div",
          { staticClass: "container" },
          [
            _c("h2", { staticClass: "index_h2" }, [
              _vm._v(_vm._s(_vm.$t("title other"))),
            ]),
            _vm._v(" "),
            _vm._m(0),
            _vm._v(" "),
            _c("h3", [
              _c("span", {
                domProps: {
                  innerHTML: _vm._s(this.$siteData.other_activity_description),
                },
              }),
            ]),
            _vm._v(" "),
            _vm._l(_vm.other_articles, function (other_article) {
              return _c("articleCardComponent", {
                key: other_article.id,
                attrs: {
                  image_dir: "images/other_img/",
                  article: other_article,
                  route: "other/" + other_article.url_title,
                },
              })
            }),
          ],
          2
        ),
      ])
    : _vm._e()
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-dribbble" }),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/SwiperComponent.vue?vue&type=template&id=7794a48e&scoped=true& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "swiper" }, [
    _c(
      "div",
      {
        staticClass: "swiper_sizing",
        style: "margin-left:" + "-" + -100 * _vm.carrent_slider_index + "%;",
      },
      _vm._l(_vm.slides, function (slide) {
        return _c(
          "div",
          { key: slide.id, staticClass: "head_slider" },
          [
            _c("site-img", {
              class: "slider_img",
              attrs: { src: slide.image, alt: slide.title },
            }),
          ],
          1
        )
      }),
      0
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/TechtipsComponent.vue?vue&type=template&id=2cc95823&scoped=true& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    { staticClass: "h-recent-work services", attrs: { id: "services" } },
    [
      _vm.techtips.length > 0
        ? _c("div", { staticClass: "container" }, [
            _c("h2", { staticClass: "index_h2" }, [
              _vm._v(_vm._s(_vm.$t("title tech tips"))),
            ]),
            _vm._v(" "),
            _vm._m(0),
            _vm._v(" "),
            _c("h3", [
              _c("span", {
                domProps: {
                  innerHTML: _vm._s(this.$siteData.tech_tips_description),
                },
              }),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "container" }, [
              _c(
                "div",
                { staticClass: "row" },
                _vm._l(_vm.techtips, function (tip) {
                  return _c(
                    "div",
                    { key: tip.id, staticClass: "col-xs-6 col-sm-6 col-md-3" },
                    [
                      tip.new_flag
                        ? _c("div", { staticClass: "product-image" }, [
                            _c(
                              "div",
                              {
                                staticClass:
                                  "discount-percent-badge discount_percent_badge_for_techtip discount-badge-fourty",
                              },
                              [_vm._v("NEW")]
                            ),
                          ])
                        : _vm._e(),
                      _vm._v(" "),
                      _c(
                        "div",
                        { staticClass: "thumbnail" },
                        [
                          _c(
                            "router-link",
                            {
                              staticClass: "info",
                              attrs: { to: "tech_tip/" + tip.url_title },
                            },
                            [
                              tip.image != null
                                ? _c("site-img", {
                                    attrs: {
                                      src: this.image_dir + tip.image,
                                      img_class: "img-responsive",
                                      alt: tip[0][0].title,
                                    },
                                  })
                                : _c("site-img", {
                                    attrs: {
                                      src: "../../../public/images/site_img/image.png",
                                      img_class: "img-responsive",
                                      alt: tip[0][0].title,
                                    },
                                  }),
                            ],
                            1
                          ),
                          _vm._v(" "),
                          _c(
                            "div",
                            { staticClass: "caption" },
                            [
                              _c(
                                "router-link",
                                {
                                  staticClass: "info",
                                  attrs: { to: "tech_tip/" + tip.url_title },
                                },
                                [_c("h3", [_vm._v(_vm._s(tip[0][0].title))])]
                              ),
                              _vm._v(" "),
                              _c("p", [
                                _vm._v(_vm._s(tip[0][0].short_description)),
                              ]),
                            ],
                            1
                          ),
                        ],
                        1
                      ),
                    ]
                  )
                }),
                0
              ),
            ]),
          ])
        : _vm._e(),
    ]
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-exclamation-triangle" }),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/WhatWeDoComponent.vue?vue&type=template&id=02488a74& ***!
  \****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return this.$siteData.map
    ? _c("div", { staticClass: "services" }, [
        _c("div", { staticClass: "container" }, [
          _c("h2", { staticClass: "index_h2" }, [
            _vm._v(_vm._s(_vm.$t("title what we do"))),
          ]),
          _vm._v(" "),
          _vm._m(0),
          _vm._v(" "),
          _c("h3", [
            _vm._v(" " + _vm._s(this.$siteData.what_we_do_description) + " "),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-xs-6 col-sm-6 col-md-6 col-lg-6" }, [
              _c("ul", { staticClass: "right-text" }, [
                _c("li", [
                  _c(
                    "h4",
                    [
                      _c(
                        "router-link",
                        {
                          staticStyle: { "font-size": "1.5em" },
                          attrs: { to: { name: "outdoors" }, exact: "" },
                        },
                        [
                          _c("strong", [
                            _vm._v(_vm._s(_vm.$t("title outdoor climbing"))),
                          ]),
                          _vm._v(" "),
                          _c("img", {
                            staticClass: "index_category_right",
                            attrs: {
                              alt: "Outdoor climbing in Georgia",
                              src: "/images/site_img/outdoor.png",
                              height: "auto",
                            },
                          }),
                        ]
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "content-text" }, [
                    _c("span", {
                      domProps: {
                        innerHTML: _vm._s(this.$siteData.indoor_description),
                      },
                    }),
                  ]),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "clearfix" }),
                _vm._v(" "),
                _c("li", [
                  _c(
                    "h4",
                    [
                      _c(
                        "router-link",
                        {
                          staticStyle: { "font-size": "1.5em" },
                          attrs: { to: { name: "indoors" }, exact: "" },
                        },
                        [
                          _c("strong", [
                            _vm._v(_vm._s(_vm.$t("title indoor climbing"))),
                          ]),
                          _vm._v(" "),
                          _c("img", {
                            staticClass: "index_category_right",
                            attrs: {
                              alt: "Indoor climbing",
                              src: "/images/site_img/indoor.png",
                            },
                          }),
                        ]
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "content-text" }, [
                    _c("span", {
                      domProps: {
                        innerHTML: _vm._s(this.$siteData.indoor_description),
                      },
                    }),
                  ]),
                ]),
              ]),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-xs-6 col-sm-6 col-md-6 col-lg-6" }, [
              _c("ul", { staticClass: "left-text" }, [
                _c("li", [
                  _c(
                    "h4",
                    [
                      _c(
                        "router-link",
                        {
                          attrs: { to: { name: "mountaineerings" }, exact: "" },
                        },
                        [
                          _c("strong", [
                            _vm._v(_vm._s(_vm.$t("title ice climbing"))),
                          ]),
                          _vm._v(" "),
                          _c("img", {
                            staticClass: "index_category_left",
                            attrs: {
                              alt: "Mountaineering in Georgia",
                              src: "/images/site_img/ice.png",
                            },
                          }),
                        ]
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "content-text" }, [
                    _c("span", {
                      domProps: {
                        innerHTML: _vm._s(this.$siteData.ice_description),
                      },
                    }),
                  ]),
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "clearfix" }),
                _vm._v(" "),
                _c("li", [
                  _c(
                    "h4",
                    [
                      _c(
                        "router-link",
                        { attrs: { to: { name: "ices" }, exact: "" } },
                        [
                          _c("strong", [
                            _vm._v(_vm._s(_vm.$t("title mountaineering"))),
                          ]),
                          _vm._v(" "),
                          _c("img", {
                            staticClass: "index_category_left",
                            attrs: {
                              alt: "Ice climbing in Georgia",
                              src: "/images/site_img/mount.png",
                            },
                          }),
                        ]
                      ),
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c("div", { staticClass: "content-text" }, [
                    _c("span", {
                      domProps: {
                        innerHTML: _vm._s(this.$siteData.mount_description),
                      },
                    }),
                  ]),
                ]),
              ]),
            ]),
          ]),
        ]),
      ])
    : _vm._e()
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-book" }),
    ])
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff&":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/ArticleCardComponent.vue?vue&type=template&id=5d4536ff& ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "food col-md-4" }, [
    _c(
      "div",
      { staticClass: "portfolio-img view view-first" },
      [
        _vm.article.new_flag
          ? _c("div", { staticClass: "product-image" }, [
              _c(
                "div",
                { staticClass: "discount-percent-badge discount-badge-fourty" },
                [_vm._v("NEW")]
              ),
            ])
          : _vm._e(),
        _vm._v(" "),
        _vm.article.image != null
          ? _c("site-img", {
              attrs: {
                src: this.image_dir + _vm.article.image,
                img_class: "img-responsive",
                alt: _vm.article[0][0].title,
              },
            })
          : _c("site-img", {
              attrs: {
                src: "../../../public/images/site_img/image.png",
                img_class: "img-responsive",
                alt: _vm.article[0][0].title,
              },
            }),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "mask" },
          [
            _c(
              "router-link",
              { staticClass: "info", attrs: { to: this.route } },
              [
                _c("i", { staticClass: "fa fa-arrow-right" }),
                _vm._v(" "),
                _c("p", { staticStyle: { display: "none" } }, [
                  _vm._v(_vm._s(_vm.article[0][0].title)),
                ]),
              ]
            ),
          ],
          1
        ),
        _vm._v(" "),
        _c("div", { staticClass: "isotope-item-dtls" }, [
          _c("h3", [_vm._v(_vm._s(_vm.article[0][0].title))]),
          _vm._v(" "),
          _c("div", { staticClass: "article_size" }, [
            _c("span", {
              domProps: {
                innerHTML: _vm._s(_vm.article[0][0].short_description),
              },
            }),
          ]),
        ]),
      ],
      1
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc&":
/*!*************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/BigNewsCardComponent.vue?vue&type=template&id=0ba77cbc& ***!
  \*************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    "section",
    { staticClass: "m-component-stack mm-component-stack--is-stacked" },
    [
      _c(
        "div",
        { staticClass: "m-image-hero mm-image-hero--ssr" },
        [
          _c("router-link", { attrs: { to: "news/" + _vm.news.url_title } }, [
            _c("div", { staticClass: "m-image-hero--media" }, [
              _c("div", [
                _c(
                  "div",
                  { staticClass: "is-loaded" },
                  [
                    _vm.news.image
                      ? _c("site-img", {
                          attrs: {
                            src: "images/news_img/" + _vm.news.image,
                            img_class: "m-image-hero--image",
                            alt: _vm.news[0][0].title,
                          },
                        })
                      : _c("site-img", {
                          attrs: {
                            src: "../../../public/images/site_img/image.png",
                            img_class: "m-image-hero--image",
                            alt: _vm.news[0][0].title,
                          },
                        }),
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "m-image-hero--aspect-ratio-wrapper" },
                  [
                    _c("div", {
                      staticClass: "m-image-hero--aspect-ratio-pusher",
                    }),
                  ]
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "m-image-hero--container" }, [
                _c(
                  "div",
                  {
                    staticClass:
                      "m-image-hero--label mm-image-hero--no-background",
                  },
                  [
                    _c("div", { staticClass: "m-image-hero--metadata-label" }, [
                      _c("div", [_vm._v("News")]),
                    ]),
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "m-image-hero--text-panel" },
                  [
                    _c(
                      "router-link",
                      { attrs: { to: "news/" + _vm.news.url_title } },
                      [
                        _c("div", [
                          _c(
                            "h2",
                            {
                              staticClass:
                                "m-ellipsis m-image-hero--header-text mm-image-hero--no-background",
                            },
                            [
                              _c("span", { staticClass: "m-ellipsis--text" }, [
                                _vm._v(_vm._s(_vm.news[0][0].title)),
                              ]),
                            ]
                          ),
                          _vm._v(" "),
                          _c("span", {
                            staticStyle: {
                              "text-shadow": "#abababb5 1px 1px 0",
                            },
                            domProps: {
                              innerHTML: _vm._s(
                                _vm.news[0][0].short_description
                              ),
                            },
                          }),
                        ]),
                      ]
                    ),
                  ],
                  1
                ),
              ]),
            ]),
          ]),
        ],
        1
      ),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/cards/NewsCardComponent.vue?vue&type=template&id=4c387c3c& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    {
      staticClass: "col-md-2",
      attrs: { "slide-id": "ci0258d58230002521", "data-pos": "183" },
    },
    [
      _c("router-link", { attrs: { to: "news/" + _vm.news.url_title } }, [
        _c(
          "article",
          {
            staticClass:
              "m-card mm-card--landscape-image mm-card--type-standard mm-card--density-strip",
            attrs: { role: "article" },
          },
          [
            _c(
              "div",
              { staticClass: "m-card--media" },
              [
                _c(
                  "router-link",
                  {
                    staticClass: "m-card--image-link",
                    attrs: { to: "news/" + _vm.news.url_title },
                  },
                  [
                    _c("div", [
                      _c(
                        "picture",
                        { staticClass: "is-loaded" },
                        [
                          _vm.news.image != _vm.NULL
                            ? _c("site-img", {
                                attrs: {
                                  src: "images/news_img/" + _vm.news.image,
                                  img_class: "m-image-hero--image",
                                  alt: _vm.news[0][0].title,
                                },
                              })
                            : _c("site-img", {
                                attrs: {
                                  src: "../../../public/images/site_img/image.png",
                                  img_class: "m-image-hero--image",
                                  alt: _vm.news[0][0].title,
                                },
                              }),
                        ],
                        1
                      ),
                      _vm._v(" "),
                      _c("div", {
                        staticClass: "m-card--image-aspect-ratio-pusher",
                      }),
                    ]),
                  ]
                ),
              ],
              1
            ),
            _vm._v(" "),
            _c("div", { staticClass: "m-card--content" }, [
              _c("div", { staticClass: "m-ellipsis m-card--header" }, [
                _c(
                  "a",
                  {
                    attrs: {
                      onclick: "return phoenixTrackClickEvent(this, event);",
                      "phx-track-id": "Title",
                      href: "#",
                    },
                  },
                  [
                    _c(
                      "h3",
                      {
                        staticClass: "m-ellipsis--text m-card--header-text",
                        attrs: { role: "heading", "aria-level": "2" },
                      },
                      [_vm._v(_vm._s(_vm.news[0][0].title))]
                    ),
                  ]
                ),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "m-ellipsis" }, [
                _c("span", {
                  staticClass: "m-card--body m-ellipsis--text",
                  domProps: {
                    innerHTML: _vm._s(_vm.news[0][0].short_description),
                  },
                }),
              ]),
            ]),
          ]
        ),
      ]),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true&":
/*!******************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/galleries/IndexGalleryComponent.vue?vue&type=template&id=7d670332&scoped=true& ***!
  \******************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "container" }, [
    _vm.db_images.length > 0
      ? _c("span", [
          _c("h2", { staticClass: "index_h2" }, [
            _vm._v(_vm._s(_vm.$t("title gallery"))),
          ]),
          _vm._v(" "),
          _vm._m(0),
          _vm._v(" "),
          _c("h3", [
            _c("span", {
              domProps: {
                innerHTML: _vm._s(this.$siteData.index_gallery_description),
              },
            }),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "container-fluid" }, [
            _c("div", { staticClass: "container" }, [
              _c(
                "div",
                { staticClass: "row" },
                _vm._l(_vm.db_images, function (image) {
                  return _c(
                    "div",
                    {
                      key: image.id,
                      staticClass: "col-xs-6 col-sm-6 col-md-3 gallery_images",
                      on: {
                        click: function ($event) {
                          return _vm.open_image(image)
                        },
                      },
                    },
                    [
                      _c("site-img", {
                        attrs: {
                          src: "../images/gallery_img/" + image.image,
                          alt: image.title,
                          img_class: "gallery_img",
                        },
                      }),
                    ],
                    1
                  )
                }),
                0
              ),
            ]),
            _vm._v(" "),
            _vm.open_img
              ? _c(
                  "div",
                  { staticClass: "open_img" },
                  [
                    _c(
                      "div",
                      { staticClass: "close_bottom" },
                      [
                        _c(
                          "bottom",
                          {
                            on: {
                              click: function ($event) {
                                return _vm.close_image(_vm.image)
                              },
                            },
                          },
                          [_vm._v("X")]
                        ),
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("site-img", {
                      attrs: {
                        src: "../images/gallery_img/" + _vm.active_img.image,
                        alt: _vm.active_img.image,
                        img_class: "gallery_big_img",
                      },
                    }),
                    _vm._v(" "),
                    _c("div", { staticClass: "image_moving" }, [
                      _c(
                        "div",
                        {
                          staticClass: "previes_img_bottom",
                          on: {
                            click: function ($event) {
                              return _vm.previes_image()
                            },
                          },
                        },
                        [
                          _c("i", {
                            staticClass: "fa fa-chevron-left",
                            attrs: { "aria-hidden": "true" },
                          }),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass: "next_img_bottom",
                          on: {
                            click: function ($event) {
                              return _vm.next_image()
                            },
                          },
                        },
                        [
                          _c("i", {
                            staticClass: "fa fa-chevron-right",
                            attrs: { "aria-hidden": "true" },
                          }),
                        ]
                      ),
                    ]),
                  ],
                  1
                )
              : _vm._e(),
          ]),
        ])
      : _vm._e(),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "bar", staticStyle: { "margin-bottom": "5%" } },
      [
        _c("i", {
          staticClass: "fa fa-picture-o",
          attrs: { "aria-hidden": "true" },
        }),
      ]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/IndexPageComponent.vue?vue&type=template&id=2de6ebd4& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

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
    [
      _c("h1", { staticStyle: { display: "none" } }, [
        _c("span", {
          domProps: { innerHTML: _vm._s(this.$siteData.guid_title) },
        }),
      ]),
      _vm._v(" "),
      _c("swiperComponent"),
      _vm._v(" "),
      _c("whatWeDoComponent"),
      _vm._v(" "),
      this.$siteData.map
        ? _c("div", { staticClass: "container h-recent-work" }, [
            _c("h2", { staticClass: "index_h2" }, [
              _vm._v(_vm._s(_vm.$t("title topo"))),
            ]),
            _vm._v(" "),
            _vm._m(0),
            _vm._v(" "),
            _c("h3", [
              _vm._v(" " + _vm._s(this.$siteData.topo_description) + " "),
            ]),
            _vm._v(" "),
            _c("div", { style: "margin-bottom: 7%" }, [
              _c("span", {
                domProps: { innerHTML: _vm._s(this.$siteData.map) },
              }),
            ]),
          ])
        : _vm._e(),
      _vm._v(" "),
      _vm.lastNews
        ? _c("div", { staticClass: "h-recent-work" }, [
            _c("div", { staticClass: "container" }, [
              _c("h2", { staticClass: "index_h2", attrs: { id: "news" } }, [
                _vm._v(_vm._s(_vm.$t("title news"))),
              ]),
              _vm._v(" "),
              _vm._m(1),
              _vm._v(" "),
              _c("h3", [
                _c("span", {
                  domProps: {
                    innerHTML: _vm._s(this.$siteData.news_description),
                  },
                }),
              ]),
              _vm._v(" "),
              _vm.lastNews
                ? _c(
                    "span",
                    [_c("bigNewsCard", { attrs: { news: _vm.lastNews } })],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.newses.length > 6
                ? _c(
                    "section",
                    {
                      staticClass:
                        "m-collection-strip mm-collection-strip--is-stretched m-component-stack mm-component-stack--is-stacked mm-component-stack--has-header",
                    },
                    [
                      _c(
                        "div",
                        { staticClass: "m-collection-strip--carousel" },
                        [
                          _c(
                            "div",
                            {
                              staticClass:
                                "m-collection-strip--carousel-wrapper",
                            },
                            [
                              _c(
                                "div",
                                {
                                  staticClass:
                                    "m-collection-strip--carousel-container",
                                },
                                [
                                  _c(
                                    "div",
                                    {
                                      staticClass:
                                        "m-collection-strip--carousel-content",
                                    },
                                    _vm._l(_vm.newses, function (news) {
                                      return _c("newsCard", {
                                        key: news.id,
                                        attrs: { news: news },
                                      })
                                    }),
                                    1
                                  ),
                                ]
                              ),
                            ]
                          ),
                        ]
                      ),
                    ]
                  )
                : _vm._e(),
            ]),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("eventComponent"),
      _vm._v(" "),
      _c("otherArticlesComponent"),
      _vm._v(" "),
      _c("techtipsComponent"),
      _vm._v(" "),
      _c("indexGalleryComponent"),
      _vm._v(" "),
      _c("metaData", {
        attrs: {
          title: "Climbing in Georgia",
          description:
            "Rock climbing, mountaineering and other outdoor actyvity in Georgia",
          image: "../../../../public/images/meta_img/outdoor.jpg",
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
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-map-marker" }),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "bar" }, [
      _c("i", { staticClass: "fa fa-newspaper-o" }),
    ])
  },
]
render._withStripped = true



/***/ })

}]);
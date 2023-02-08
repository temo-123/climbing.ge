"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_shop_pages_ServicePageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      general_pathname: window.location.pathname,
      path: '',
      page: '',
      path_url: ''
    };
  },
  mounted: function mounted() {
    this.page = this.general_pathname.split("/").pop();
    var path = this.general_pathname.split("/")[1];
    this.path = path;

    if (this.path == 'product') {
      this.path_url = 'catalog';
    } else {
      this.path_url = path + 's';
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
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
      title: this.title + ' - ' + 'shop.' + this.mix_site_url,
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
        titleTemplate: '%s | ' + this.title + ' - ' + 'shop.' + this.mix_site_url
      }, {
        canonical: 'Default App Title',
        content: '%s | ' + this.title + ' - ' + 'shop.' + this.mix_site_url
      }, // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
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
        content: this.mix_app_ssh + 'shop.' + this.mix_site_url
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
        content: this.mix_app_ssh + 'shop.' + this.mix_site_url
      }, {
        name: 'twitter:title',
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
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
        content: this.title + ' - ' + 'shop.' + this.mix_site_url
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************/
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
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['service_data'],
  data: function data() {
    return {};
  },
  components: {},
  mounted: function mounted() {},
  methods: {}
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_ServiceItemComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../items/ServiceItemComponent */ "./resources/js/components/shop/items/ServiceItemComponent.vue");
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../items/MetaDataComponent */ "./resources/js/components/shop/items/MetaDataComponent.vue");
/* harmony import */ var _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../items/BreadcrumbComponent.vue */ "./resources/js/components/shop/items/BreadcrumbComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    ServiceItem: _items_ServiceItemComponent__WEBPACK_IMPORTED_MODULE_0__["default"],
    breadcrumb: _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  props: ['data'],
  data: function data() {
    return {
      services: [],
      setvice: []
    };
  },
  watch: {
    '$route': function $route(to, from) {
      // this.get_services()
      this.get_service();
      window.scrollTo(0, 0);
    }
  },
  mounted: function mounted() {
    this.get_service();
  },
  methods: {
    get_services: function get_services(activ_service_id) {
      var _this = this;

      axios.get('../api/similar_services/' + localStorage.getItem('lang') + '/' + activ_service_id).then(function (response) {
        _this.services = response.data;
      })["catch"](function (error) {});
    },
    get_service: function get_service() {
      var _this2 = this;

      axios.get('../api/service/' + localStorage.getItem('lang') + '/' + this.$route.params.url_title).then(function (response) {
        _this2.setvice = response.data[0];

        _this2.get_services(_this2.setvice.global_service.id);
      })["catch"](function (error) {});
    }
  }
});

/***/ }),

/***/ "./resources/js/components/shop/items/BreadcrumbComponent.vue":
/*!********************************************************************!*\
  !*** ./resources/js/components/shop/items/BreadcrumbComponent.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./BreadcrumbComponent.vue?vue&type=template&id=2802217d& */ "./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d&");
/* harmony import */ var _BreadcrumbComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./BreadcrumbComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _BreadcrumbComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__.render,
  _BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/items/BreadcrumbComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=24818c71& */ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/items/ServiceItemComponent.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/components/shop/items/ServiceItemComponent.vue ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ServiceItemComponent.vue?vue&type=template&id=40bc6842& */ "./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842&");
/* harmony import */ var _ServiceItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ServiceItemComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ServiceItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__.render,
  _ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/items/ServiceItemComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/pages/ServicePageComponent.vue":
/*!*********************************************************************!*\
  !*** ./resources/js/components/shop/pages/ServicePageComponent.vue ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ServicePageComponent.vue?vue&type=template&id=673acec2& */ "./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2&");
/* harmony import */ var _ServicePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ServicePageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ServicePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__.render,
  _ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/pages/ServicePageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BreadcrumbComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./BreadcrumbComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_BreadcrumbComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServiceItemComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceItemComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicePageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_BreadcrumbComponent_vue_vue_type_template_id_2802217d___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./BreadcrumbComponent.vue?vue&type=template&id=2802217d& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d&");


/***/ }),

/***/ "./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_24818c71___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=24818c71& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&");


/***/ }),

/***/ "./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServiceItemComponent_vue_vue_type_template_id_40bc6842___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServiceItemComponent.vue?vue&type=template&id=40bc6842& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842&");


/***/ }),

/***/ "./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_vue_vue_type_template_id_673acec2___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicePageComponent.vue?vue&type=template&id=673acec2& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d&":
/*!******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/BreadcrumbComponent.vue?vue&type=template&id=2802217d& ***!
  \******************************************************************************************************************************************************************************************************************************************/
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
    "ol",
    {
      staticClass: "breadcrumb",
      staticStyle: { padding: "0", "font-size": "75%" },
    },
    [
      _c(
        "li",
        [
          _c("router-link", { attrs: { to: { name: "catalog" } } }, [
            _vm._v("Home"),
          ]),
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "li",
        [
          _c("router-link", { attrs: { to: { name: _vm.path_url } } }, [
            _vm._v(_vm._s(_vm.path)),
          ]),
        ],
        1
      ),
      _vm._v(" "),
      _c("li", { staticClass: "active" }, [_vm._v(_vm._s(_vm.page))]),
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71&":
/*!****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/MetaDataComponent.vue?vue&type=template&id=24818c71& ***!
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
  return _c("div")
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/items/ServiceItemComponent.vue?vue&type=template&id=40bc6842& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "col-md-3 col-sm-6" }, [
    _c("div", { staticClass: "service-box " }, [
      _vm.service_data.image.length > 0
        ? _c(
            "div",
            {
              staticClass: "service-icon red services_bacground_image",
              style:
                "background-image: url(../../images/service_img/" +
                _vm.service_data.image +
                ");",
            },
            [
              _c("div", { staticClass: "front-content" }, [
                _c("h3", { staticClass: "services-title" }, [
                  _vm._v(_vm._s(_vm.service_data[0][0].title)),
                ]),
              ]),
            ]
          )
        : _c(
            "div",
            { staticClass: "service-icon red services_bacground_image" },
            [
              _c("div", { staticClass: "front-content" }, [
                _c("h3", { staticClass: "services-title" }, [
                  _vm._v(_vm._s(_vm.service_data[0][0].title)),
                ]),
              ]),
            ]
          ),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "service-content" },
        [
          _c(
            "router-link",
            { attrs: { to: "../service/" + _vm.service_data.url_title } },
            [_c("h3", [_vm._v(_vm._s(_vm.service_data[0][0].title))])]
          ),
          _vm._v(" "),
          _c("p", [_vm._v(_vm._s(_vm.service_data[0][0].short_description))]),
        ],
        1
      ),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/ServicePageComponent.vue?vue&type=template&id=673acec2& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "container top_menu_margin h-recent-work" },
    [
      _c("h1", { staticClass: "page_title" }, [
        _vm._v(_vm._s(_vm.setvice.local_service.title)),
      ]),
      _vm._v(" "),
      _c("breadcrumb"),
      _vm._v(" "),
      _vm.setvice.service_images
        ? _c("div", { staticClass: "row" }, [
            _c("img", {
              staticClass: "service_page_image",
              attrs: {
                src:
                  "../../images/service_img/" +
                  _vm.setvice.service_images[0].image,
                alt: _vm.setvice.local_service.title,
              },
            }),
          ])
        : _vm._e(),
      _vm._v(" "),
      _c("div", { staticClass: "row service_page_text" }, [
        _vm._v(
          "\n        " + _vm._s(_vm.setvice.local_service.text) + "\n    "
        ),
      ]),
      _vm._v(" "),
      this.services.length > 0
        ? _c(
            "div",
            { staticClass: "row" },
            [
              _c("hr"),
              _vm._v(" "),
              _c("h2", { staticClass: "other_servces" }, [
                _vm._v("other service"),
              ]),
              _vm._v(" "),
              _vm._l(_vm.services, function (service) {
                return _c("ServiceItem", {
                  key: service.id,
                  attrs: { service_data: service },
                })
              }),
            ],
            2
          )
        : _vm._e(),
      _vm._v(" "),
      _c("metaData", {
        attrs: {
          title: _vm.setvice.local_service.title,
          description: _vm.setvice.local_service.short_description,
          image:
            "../../images/service_img/" + _vm.setvice.service_images[0].image,
        },
      }),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);
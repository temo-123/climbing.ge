"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_blog_pages_PostPage_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  inject: ['siteData'],
  metaInfo: function metaInfo() {
    return {
      title: 'test title',
      htmlAttrs: {
        lang: 'en-US'
      },
      link: [{
        rel: 'stylesheet',
        href: '/css/index.css'
      }, {
        rel: 'canonical',
        href: 'climbing.loc'
      }],
      meta: [{
        name: 'viewport',
        content: 'width=device-width, initial-scale=1'
      }, {
        charset: 'utf-8'
      }, {
        name: 'description',
        content: 'An example Vue application with vue-meta.'
      }, {
        title: 'Default App Title',
        titleTemplate: '%s | vue-meta Example App'
      }, {
        canonical: 'Default App Title',
        content: '%s | vue-meta Example App'
      },
      // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: 'My Page Title ← My Site'
      }, {
        property: 'og:site_name',
        content: 'My Site'
      },
      // The list of types is available here: http://ogp.me/#types
      {
        property: 'og:type',
        content: 'website'
      },
      // Should the the same as your canonical link, see below.
      {
        property: 'og:url',
        content: 'https://www.my-site.com/my-special-page'
      }, {
        property: 'og:image',
        content: '../../../../public/images./meta_images/outdoor.jpg'
      },
      // Often the same as your meta description, but not always.
      {
        property: 'og:description',
        content: 'I have things here on my site.'
      },
      // Twitter card
      {
        name: 'twitter:card',
        content: 'summary'
      }, {
        name: 'twitter:site',
        content: 'https://www.my-site.com/my-special-page'
      }, {
        name: 'twitter:title',
        content: 'My Page Title ← My Site'
      }, {
        name: 'twitter:description',
        content: 'I have things here on my site.'
      },
      // Your twitter handle, if you have one.
      {
        name: 'twitter:creator',
        content: '@alligatorio'
      }, {
        name: 'twitter:image:src',
        content: '../../../../public/images./meta_images/outdoor.jpg'
      },
      // Google / Schema.org markup:
      {
        itemprop: 'name',
        content: 'My Page Title ← My Site'
      }, {
        itemprop: 'description',
        content: 'I have things here on my site.'
      }, {
        itemprop: 'image',
        content: '../../../../public/images./meta_images/outdoor.jpg'
      }]
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../items/MetaDataComponent */ "./resources/js/components/blog/items/MetaDataComponent.vue");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  name: 'PostPage',
  components: {
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      post: null,
      loading: true
    };
  },
  mounted: function mounted() {
    this.fetchPost();
  },
  methods: {
    fetchPost: function fetchPost() {
      var _this = this;
      var postId = this.$route.params.id;
      axios__WEBPACK_IMPORTED_MODULE_0___default().get("/api/blog/get_post/".concat(postId)).then(function (response) {
        _this.post = response.data;
      })["catch"](function (error) {
        console.error('Error fetching post:', error);
      })["finally"](function () {
        _this.loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6& ***!
  \**********************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div");
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function render() {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", [_c("main", {
    staticClass: "container",
    attrs: {
      role: "main"
    }
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-8 blog-main"
  }, [_c("section", {
    staticClass: "hero"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("div", {
    staticClass: "row"
  }, [_vm.loading ? _c("div", {
    staticClass: "text-center"
  }, [_c("p", [_vm._v("Loading post...")])]) : _vm.post ? _c("div", [_c("h1", [_vm._v(_vm._s(_vm.post.title))]), _vm._v(" "), _c("p", [_vm._v(_vm._s(_vm.post.content))]), _vm._v(" "), _c("small", [_vm._v("Created at: " + _vm._s(_vm.post.created_at))])]) : _c("div", [_c("p", [_vm._v("Post not found.")])])])])])]), _vm._v(" "), _c("rightMenu")], 1)]), _vm._v(" "), _c("metaData", {
    attrs: {
      title: _vm.$t("blog.meta.post"),
      description: this.$siteData.data.guid_short_description,
      image: "/public/images/meta_img/outdoor.jpg"
    }
  })], 1);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/blog/items/MetaDataComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/blog/items/MetaDataComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=0dbd31c6& */ "./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/blog/items/MetaDataComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/blog/pages/PostPage.vue":
/*!*********************************************************!*\
  !*** ./resources/js/components/blog/pages/PostPage.vue ***!
  \*********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./PostPage.vue?vue&type=template&id=4e9dc5ec& */ "./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec&");
/* harmony import */ var _PostPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./PostPage.vue?vue&type=script&lang=js& */ "./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _PostPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__.render,
  _PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/blog/pages/PostPage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PostPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PostPage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_PostPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_0dbd31c6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=0dbd31c6& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/items/MetaDataComponent.vue?vue&type=template&id=0dbd31c6&");


/***/ }),

/***/ "./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec&":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec& ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_PostPage_vue_vue_type_template_id_4e9dc5ec___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./PostPage.vue?vue&type=template&id=4e9dc5ec& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/blog/pages/PostPage.vue?vue&type=template&id=4e9dc5ec&");


/***/ })

}]);
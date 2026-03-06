"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_site_pages_UnfollowComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _unhead_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @unhead/vue */ "./node_modules/@unhead/vue/dist/shared/vue.f36acd1f.mjs");
/* harmony import */ var _unhead_vue_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @unhead/vue/components */ "./node_modules/@unhead/vue/dist/components.mjs");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['title', 'description', 'image'],
  components: {
    useHead: _unhead_vue__WEBPACK_IMPORTED_MODULE_0__.u,
    Head: _unhead_vue_components__WEBPACK_IMPORTED_MODULE_1__.Head
  },
  data: function data() {
    return {
      locale: localStorage.getItem('lang'),
      mix_site_url: "climbing.loc",
      mix_app_ssh: "http://",
      mix_site_name: "climbing.ge",
      action_image: "http://" + '' + "climbing.loc" + '/' + this.image
    };
  },
  // useHead: {
  //     title: this.title + ' - ' + this.mix_site_url,
  //     meta: [
  //         {
  //             name: 'description',
  //             content: 'My page description',
  //             image: 'action_image'
  //         },
  //     ],
  // },
  metaInfo: function metaInfo() {
    return {
      title: this.title + ' - ' + this.mix_site_url,
      htmlAttrs: {
        lang: this.locale,
        amp: true
      },
      link: [
      // { rel: 'stylesheet', href: '/css/index.css' },
      {
        rel: 'canonical',
        href: window.location.pathname
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
      },
      // https://www.digitalocean.com/community/tutorials/vuejs-vue-seo-tips
      // OpenGraph data (Most widely used)
      {
        property: 'og:title',
        content: this.title + ' - ' + this.mix_site_url
      }, {
        property: 'og:site_name',
        content: this.mix_site_name
      },
      // The list of types is available here: http://ogp.me/#types
      {
        property: 'og:type',
        content: 'website'
      },
      // Should the the same as your canonical link, see below.
      {
        property: 'og:url',
        content: this.mix_app_ssh + this.mix_site_url
      }, {
        property: 'og:image',
        content: this.action_image
      },
      // Often the same as your meta description, but not always.
      {
        property: 'og:description',
        content: this.description
      },
      // Twitter card
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
      },
      // Your twitter handle, if you have one.
      {
        name: 'twitter:creator',
        content: '@alligatorio'
      }, {
        name: 'twitter:image:src',
        content: this.action_image
      },
      // Google / Schema.org markup:
      {
        itemprop: 'name',
        content: this.title + ' - ' + this.mix_site_url
      }, {
        itemprop: 'description',
        content: this.description
      }, {
        itemprop: 'image',
        content: this.action_image
      }]
    };
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _global_components_ServicesListComponent_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../global_components/ServicesListComponent.vue */ "./resources/js/components/global_components/ServicesListComponent.vue");
/* harmony import */ var _global_components_MessageComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../global_components/MessageComponent */ "./resources/js/components/global_components/MessageComponent.vue");
/* harmony import */ var _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../items/MetaDataComponent */ "./resources/js/components/site/items/MetaDataComponent.vue");



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    metaData: _items_MetaDataComponent__WEBPACK_IMPORTED_MODULE_2__["default"],
    messageComponent: _global_components_MessageComponent__WEBPACK_IMPORTED_MODULE_1__["default"],
    servicesListComponent: _global_components_ServicesListComponent_vue__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      message: [],
      loading: false
    };
  },
  mounted: function mounted() {
    this.unfollow();
  },
  methods: {
    unfollow: function unfollow() {
      var _this = this;
      this.loading = true;
      if (confirm('Are you sure, you want unfollow?')) {
        axios.post('/follow/del_follower/' + this.$route.params.id, {
          _method: 'DELETE'
        }).then(function (Response) {
          alert(Response.data);
        })["catch"](function (error) {
          // console.log(error)
        })["finally"](function () {
          return _this.loading = false;
        });
      } else {
        this.loading = false;
        this.$router.push({
          name: 'index'
        });
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42& ***!
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
  return _c("Head", [_c("title", [_vm._v(_vm._s(this.title + " - " + this.mix_site_url))]), _vm._v(" "), _c("meta", {
    attrs: {
      name: "description",
      content: this.description
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "keywords",
      content: "climbing, georgia, climbing in georgia, rock climbing, rock climing in georgia, mountaineering, mountaineering in georgia"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "subject",
      content: "Rock climbing and mountaineering in Georgia"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "copyright",
      content: "Temo Samsonadze"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "language",
      content: this.locale
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "Classification",
      content: "Sport"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "author",
      content: "info@" + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "reply-to",
      content: "info@" + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:title",
      content: this.title
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:type",
      content: "Climbing"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:image",
      content: this.action_image
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:site_name",
      content: this.mix_site_name
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "og:description",
      content: this.description
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:title",
      content: this.title
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:type",
      content: "Climbing"
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:url",
      content: this.mix_app_ssh + this.mix_site_url
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:image",
      content: this.action_image
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:site_name",
      content: this.mix_site_name
    }
  }), _vm._v(" "), _c("meta", {
    attrs: {
      name: "twitter:description",
      content: this.description
    }
  })]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb&":
/*!**********************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb& ***!
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
  return _c("div", {
    staticClass: "container"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_vm.loading ? _c("div", {
    staticClass: "error-template"
  }, [_c("img", {
    attrs: {
      src: "../../../public/images/site_img/loading.gif",
      alt: "loading"
    }
  })]) : _vm._e(), _vm._v(" "), !_vm.loading ? _c("div", {
    staticClass: "error-template"
  }, [_c("h1", [_vm._v(" " + _vm._s(_vm.$t("unfollow.title")) + " ")]), _vm._v(" "), _c("div", {
    staticClass: "error-details"
  }, [_vm._v("\n                    " + _vm._s(_vm.$t("unfollow.description")) + "\n                ")]), _vm._v(" "), _c("div", {
    staticClass: "error-actions"
  }, [_c("router-link", {
    staticClass: "btn btn-primary btn-lg",
    attrs: {
      to: "/",
      exact: ""
    }
  }, [_c("span", {
    staticClass: "glyphicon glyphicon-home"
  }), _vm._v("\n                        " + _vm._s(_vm.$t("unfollow.go_home")) + "\n                    ")])], 1), _vm._v(" "), _c("h2", [_vm._v("By Climbing.ge")])]) : _vm._e()])])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
___CSS_LOADER_EXPORT___.push([module.id, "\n/*  */\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_style_index_0_id_0a7bfdeb_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_style_index_0_id_0a7bfdeb_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_style_index_0_id_0a7bfdeb_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

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
/* harmony import */ var _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=template&id=765add42& */ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&");
/* harmony import */ var _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MetaDataComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.render,
  _MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
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

/***/ "./resources/js/components/site/pages/UnfollowComponent.vue":
/*!******************************************************************!*\
  !*** ./resources/js/components/site/pages/UnfollowComponent.vue ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./UnfollowComponent.vue?vue&type=template&id=0a7bfdeb& */ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb&");
/* harmony import */ var _UnfollowComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./UnfollowComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _UnfollowComponent_vue_vue_type_style_index_0_id_0a7bfdeb_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& */ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _UnfollowComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__.render,
  _UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/site/pages/UnfollowComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

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
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./UnfollowComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MetaDataComponent_vue_vue_type_template_id_765add42___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MetaDataComponent.vue?vue&type=template&id=765add42& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/items/MetaDataComponent.vue?vue&type=template&id=765add42&");


/***/ }),

/***/ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb&":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb& ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_template_id_0a7bfdeb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./UnfollowComponent.vue?vue&type=template&id=0a7bfdeb& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=template&id=0a7bfdeb&");


/***/ }),

/***/ "./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_UnfollowComponent_vue_vue_type_style_index_0_id_0a7bfdeb_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/site/pages/UnfollowComponent.vue?vue&type=style&index=0&id=0a7bfdeb&lang=css&");


/***/ }),

/***/ "./node_modules/@unhead/vue/dist/components.mjs":
/*!******************************************************!*\
  !*** ./node_modules/@unhead/vue/dist/components.mjs ***!
  \******************************************************/
/***/ ((__unused_webpack___webpack_module__, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "Head": () => (/* binding */ Head)
/* harmony export */ });
/* harmony import */ var vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue */ "./node_modules/vue/dist/vue.esm.js");
/* harmony import */ var _shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./shared/vue.cf295fb1.mjs */ "./node_modules/@unhead/vue/dist/shared/vue.cf295fb1.mjs");





function addVNodeToHeadObj(node, obj) {
  const nodeType = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? node.tag : node.type;
  const type = nodeType === "html" ? "htmlAttrs" : nodeType === "body" ? "bodyAttrs" : nodeType;
  if (typeof type !== "string" || !(type in obj))
    return;
  const nodeData = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? node.data : node;
  const props = (!_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? nodeData.attrs : node.props) || {};
  if (!_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V) {
    if (nodeData.staticClass)
      props.class = nodeData.staticClass;
    if (nodeData.staticStyle)
      props.style = Object.entries(nodeData.staticStyle).map(([key, value]) => `${key}:${value}`).join(";");
  }
  if (node.children) {
    const childrenAttr = !_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.V ? "text" : "children";
    props.children = Array.isArray(node.children) ? node.children[0][childrenAttr] : node[childrenAttr];
  }
  if (Array.isArray(obj[type]))
    obj[type].push(props);
  else if (type === "title")
    obj.title = props.children;
  else
    obj[type] = props;
}
function vnodesToHeadObj(nodes) {
  const obj = {
    title: void 0,
    htmlAttrs: void 0,
    bodyAttrs: void 0,
    base: void 0,
    meta: [],
    link: [],
    style: [],
    script: [],
    noscript: []
  };
  for (const node of nodes) {
    if (typeof node.type === "symbol" && Array.isArray(node.children)) {
      for (const childNode of node.children)
        addVNodeToHeadObj(childNode, obj);
    } else {
      addVNodeToHeadObj(node, obj);
    }
  }
  return obj;
}
const Head = /* @__PURE__ */ (0,vue__WEBPACK_IMPORTED_MODULE_1__.defineComponent)({
  name: "Head",
  setup(_, { slots }) {
    const head = (0,_shared_vue_cf295fb1_mjs__WEBPACK_IMPORTED_MODULE_0__.i)();
    const obj = (0,vue__WEBPACK_IMPORTED_MODULE_1__.ref)({});
    const entry = head.push(obj);
    (0,vue__WEBPACK_IMPORTED_MODULE_1__.onBeforeUnmount)(() => {
      entry.dispose();
    });
    return () => {
      (0,vue__WEBPACK_IMPORTED_MODULE_1__.watchEffect)(() => {
        if (!slots.default)
          return;
        entry.patch(vnodesToHeadObj(slots.default()));
      });
      return null;
    };
  }
});




/***/ })

}]);
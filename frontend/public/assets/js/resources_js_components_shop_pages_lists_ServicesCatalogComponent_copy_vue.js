"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_shop_pages_lists_ServicesCatalogComponent_copy_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/ServiceItemComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
/* harmony import */ var vue_content_loader__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! vue-content-loader */ "./node_modules/vue-content-loader/dist/vue-content-loader.es.js");
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../global_components/EmptyPageComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/MetaDataComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    metaData: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/MetaDataComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    ServiceItem: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/ServiceItemComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    ContentLoader: vue_content_loader__WEBPACK_IMPORTED_MODULE_1__.ContentLoader,
    emptyPageComponent: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../global_components/EmptyPageComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())
  },
  data: function data() {
    return {
      services: [],
      site_data: [],
      services_loading: true
    };
  },
  mounted: function mounted() {
    this.get_services();
  },
  methods: {
    get_services: function get_services() {
      var _this = this;
      axios.get('../api/services/' + localStorage.getItem('lang')).then(function (response) {
        _this.services = response.data;
      })["catch"](function (error) {})["finally"](function () {
        return _this.services_loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "col-md-12"
  }, [_c("h1", {
    staticClass: "page_title"
  }, [_vm._v(_vm._s(_vm.$t("shop.title.services")))]), _vm._v(" "), _vm._m(0), _vm._v(" "), _c("h2", {
    staticClass: "article_list_short_description"
  }, [_c("span", {
    domProps: {
      innerHTML: _vm._s(this.$siteData.services_description)
    }
  })]), _vm._v(" "), _vm.services.length ? _c("span", [_vm.services_loading ? _c("div", {
    staticClass: "row"
  }, [_c("content-loader", {
    attrs: {
      viewBox: "0 0",
      primaryColor: "#f3f3f3",
      secondaryColor: "#27bb7d8c"
    }
  })], 1) : _c("div", {
    staticClass: "row"
  }, _vm._l(_vm.services, function (service) {
    return _c("ServiceItem", {
      key: service.id,
      attrs: {
        service_data: service
      }
    });
  }), 1)]) : _c("span", [_c("emptyPageComponent")], 1), _vm._v(" "), _c("metaData", {
    attrs: {
      title: _vm.$t("shop.meta.services"),
      description: "Service climbing & mountaineering.",
      image: "/public/images/meta_img/services.jpg"
    }
  })], 1);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "bar"
  }, [_c("i", {
    staticClass: "fa fa-exclamation-triangle"
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue":
/*!************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue ***!
  \************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40& */ "./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40&");
/* harmony import */ var _ServicesCatalogComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ServicesCatalogComponent copy.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ServicesCatalogComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__.render,
  _ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicesCatalogComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicesCatalogComponent copy.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicesCatalogComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40&":
/*!*******************************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40& ***!
  \*******************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicesCatalogComponent_copy_vue_vue_type_template_id_2bd88e40___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/lists/ServicesCatalogComponent copy.vue?vue&type=template&id=2bd88e40&");


/***/ })

}]);
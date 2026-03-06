"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_shop_pages_pages_ServicePageComponent_copy_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/ServiceItemComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/MetaDataComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/GalleryComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());




/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    metaData: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/MetaDataComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    ServiceItem: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/ServiceItemComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    breadcrumb: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    gallery: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/GalleryComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())
  },
  props: ['data'],
  data: function data() {
    return {
      services: [],
      service: []
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
        _this2.service = response.data[0];
        _this2.get_services(_this2.service.global_service.id);
      })["catch"](function (error) {});
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "container top_menu_margin h-recent-work"
  }, [_c("h1", {
    staticClass: "page_title"
  }, [_vm._v(_vm._s(_vm.service.local_service.title))]), _vm._v(" "), _vm.service.service_images.length > 0 ? _c("div", {
    staticClass: "row"
  }, [_c("img", {
    staticClass: "service_page_image",
    attrs: {
      src: "../../images/service_img/" + _vm.service.service_images[0].image,
      alt: _vm.service.local_service.title
    }
  })]) : _vm._e(), _vm._v(" "), _c("div", {
    staticClass: "row service_page_text"
  }, [_c("span", {
    domProps: {
      innerHTML: _vm._s(_vm.service.local_service.text)
    }
  })]), _vm._v(" "), _c("gallery", {
    attrs: {
      images_prop: _vm.service.service_images
    }
  }), _vm._v(" "), this.services.length > 0 ? _c("div", {
    staticClass: "row"
  }, [_c("hr"), _vm._v(" "), _c("h2", {
    staticClass: "other_servces"
  }, [_vm._v(_vm._s(_vm.$t("shop.title.similar.services")))]), _vm._v(" "), _vm._l(_vm.services, function (service) {
    return _c("ServiceItem", {
      key: service.id,
      attrs: {
        service_data: service
      }
    });
  })], 2) : _vm._e(), _vm._v(" "), _c("metaData", {
    attrs: {
      title: _vm.service.local_service.title,
      description: _vm.service.local_service.short_description,
      image: "/images/service_img/" + _vm.service.service_images[0].image
    }
  })], 1);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue":
/*!********************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue ***!
  \********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ServicePageComponent copy.vue?vue&type=template&id=0d29ed10& */ "./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10&");
/* harmony import */ var _ServicePageComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ServicePageComponent copy.vue?vue&type=script&lang=js& */ "./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ServicePageComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__.render,
  _ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/shop/pages/pages/ServicePageComponent copy.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicePageComponent copy.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_copy_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ServicePageComponent_copy_vue_vue_type_template_id_0d29ed10___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ServicePageComponent copy.vue?vue&type=template&id=0d29ed10& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/shop/pages/pages/ServicePageComponent copy.vue?vue&type=template&id=0d29ed10&");


/***/ })

}]);
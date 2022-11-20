"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_orders_allOrderPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_data_tabs_DataTab_TabsComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../items/data_tabs/DataTab/TabsComponent */ "./resources/js/components/user/items/data_tabs/DataTab/TabsComponent.vue");
/* harmony import */ var _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../items/BreadcrumbComponent.vue */ "./resources/js/components/user/items/BreadcrumbComponent.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    breadcrumb: _items_BreadcrumbComponent_vue__WEBPACK_IMPORTED_MODULE_1__["default"],
    tabsComponent: _items_data_tabs_DataTab_TabsComponent__WEBPACK_IMPORTED_MODULE_0__["default"] // leftMenu,

  },
  data: function data() {
    return {
      data_for_tab: []
    };
  },
  mounted: function mounted() {
    this.get_orders();
  },
  watch: {
    '$route': function $route(to, from) {
      this.data_for_tab = [], this.get_orders();
      window.scrollTo(0, 0);
    }
  },
  methods: {
    get_orders: function get_orders() {
      var _this = this;

      axios.get("../api/order/").then(function (response) {
        _this.data_for_tab.push({
          'id': 1,
          'data': response.data,
          'table_name': 'Orders',
          'table_category': '',
          'table_edit': '',
          'table_del': ''
        });

        _this.get_shipd_countries();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_shipd_countries: function get_shipd_countries() {
      var _this2 = this;

      axios.get("../api/get_countries/").then(function (response) {
        _this2.data_for_tab.push({
          'id': 2,
          'data': response.data,
          'table_name': 'Shiped countries',
          'table_category': '',
          'table_edit': '',
          'table_del': ''
        });
      })["catch"](function (error) {
        return console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/orders/allOrderPageComponent.vue":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/user/pages/orders/allOrderPageComponent.vue ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./allOrderPageComponent.vue?vue&type=template&id=12669fea& */ "./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea&");
/* harmony import */ var _allOrderPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./allOrderPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _allOrderPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__.render,
  _allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/orders/allOrderPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_allOrderPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./allOrderPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_allOrderPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_allOrderPageComponent_vue_vue_type_template_id_12669fea___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./allOrderPageComponent.vue?vue&type=template&id=12669fea& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/orders/allOrderPageComponent.vue?vue&type=template&id=12669fea& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "row" }, [
    _c("div", { staticClass: "col-sm-3" }, [_c("left-menu")], 1),
    _vm._v(" "),
    _c("div", { staticClass: "col-sm-9" }, [
      _c("div", { staticClass: "row" }, [
        _c("div", { staticClass: "col-md-12" }, [_c("breadcrumb")], 1),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "row" }, [
        _c(
          "div",
          { staticClass: "col-sm-12" },
          [_c("tabsComponent", { attrs: { table_data: this.data_for_tab } })],
          1
        ),
      ]),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);
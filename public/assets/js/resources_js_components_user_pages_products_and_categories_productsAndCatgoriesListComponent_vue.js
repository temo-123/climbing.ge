"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_products_and_categories_productsAndCatgoriesListComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _items_data_tabs_DataTab_TabsComponent__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../items/data_tabs/DataTab/TabsComponent */ "./resources/js/components/user/items/data_tabs/DataTab/TabsComponent.vue");
//
//
//
//
//
//
//
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
    tabsComponent: _items_data_tabs_DataTab_TabsComponent__WEBPACK_IMPORTED_MODULE_0__["default"]
  },
  data: function data() {
    return {
      data_for_tab: []
    };
  },
  mounted: function mounted() {
    this.get_products_data();
  },
  methods: {
    get_products_data: function get_products_data() {
      var _this = this;

      this.data_for_tab = [];
      axios.get("../api/products/en/").then(function (response) {
        _this.data_for_tab.push({
          'id': 1,
          'data': response.data,
          'table_name': 'Products',
          'table_add_url': 'productAdd',
          'table_edit_url': 'productEdit',
          'table_del_url': 'del_url'
        });

        _this.get_categories_data();

        _this.get_sale_codes_data();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_categories_data: function get_categories_data() {
      var _this2 = this;

      axios.get("../api/product_category").then(function (response) {
        _this2.data_for_tab.push({
          'id': 2,
          'data': response.data,
          'table_name': 'Categories',
          'table_add_url': 'productCategoryAdd',
          'table_edit_url': 'productCategoryEdit',
          'table_del_url': 'del_url'
        });
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_sale_codes_data: function get_sale_codes_data() {
      var _this3 = this;

      axios.get("../api/sale_code").then(function (response) {
        _this3.data_for_tab.push({
          'id': 3,
          'data': response.data,
          'table_name': 'Sale codes' // 'table_add_url': 'saleCodeAdd', 
          // 'table_edit_url': 'saleCodeEdit',
          // 'table_del_url': 'del_url', 

        });
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    product_del: function product_del(itemId) {
      var _this4 = this;

      axios.post('../api/products/del/' + itemId, {
        id: itemId
      }).then(function (Response) {
        console.log(response);

        _this4.get_data_in_table_1();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    callback: function callback(id) {
      if (id == 1) {
        this.get_articles();
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb& */ "./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb&");
/* harmony import */ var _productsAndCatgoriesListComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productsAndCatgoriesListComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productsAndCatgoriesListComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__.render,
  _productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productsAndCatgoriesListComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productsAndCatgoriesListComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productsAndCatgoriesListComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb&":
/*!*****************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb& ***!
  \*****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsAndCatgoriesListComponent_vue_vue_type_template_id_6c15bcbb___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb&":
/*!********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/productsAndCatgoriesListComponent.vue?vue&type=template&id=6c15bcbb& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************/
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
      _c(
        "div",
        { staticClass: "col-sm-12" },
        [
          _c("tabsComponent", {
            attrs: { table_data: this.data_for_tab },
            on: { "update-data": _vm.get_products_data },
          }),
        ],
        1
      ),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);
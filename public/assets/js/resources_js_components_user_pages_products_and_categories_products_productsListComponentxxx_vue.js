"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_products_and_categories_products_productsListComponentxxx_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @innologica/vue-stackable-modal */ "./node_modules/@innologica/vue-stackable-modal/dist/vue-stackable-modal.umd.min.js");
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default())
  },
  props: ["product_get_route", "product_name", "product_add_url", // "product_edit_url",
  // "product_del_url",
  "product_page"],
  data: function data() {
    return {
      products: [],
      product_is_refresh: false,
      product_reset_id: 0,
      url_1: ""
    };
  },
  mounted: function mounted() {
    this.get_products_data();
  },
  methods: {
    get_products_data: function get_products_data() {
      var _this = this;

      this.product_is_refresh = true;
      axios.get("./api/products/en/").then(function (response) {
        _this.products = response.data;
        _this.product_is_refresh = false;
        _this.product_reset_id++; // this.get_data_in_table_1();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    product_del: function product_del(itemId) {
      var _this2 = this;

      axios.post('/products/del/' + itemId, {
        id: itemId
      }).then(function (Response) {
        console.log(response);

        _this2.get_data_in_table_1();
      })["catch"](function (error) {
        return console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productsListComponentxxx.vue?vue&type=template&id=46c54014& */ "./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014&");
/* harmony import */ var _productsListComponentxxx_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productsListComponentxxx.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productsListComponentxxx_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__.render,
  _productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productsListComponentxxx_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productsListComponentxxx.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productsListComponentxxx_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014&":
/*!*****************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014& ***!
  \*****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_productsListComponentxxx_vue_vue_type_template_id_46c54014___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productsListComponentxxx.vue?vue&type=template&id=46c54014& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014&":
/*!********************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productsListComponentxxx.vue?vue&type=template&id=46c54014& ***!
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
  return _c("div", [
    _c("div", { staticClass: "row list-group" }, [
      _c("div", { staticClass: "add_buttom" }, [
        _c(
          "a",
          {
            staticClass: "btn btn-primary pull-left",
            attrs: { href: _vm.product_add_url, type: "submit" },
          },
          [_vm._v("New ")]
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "form-groupe" }, [
        !_vm.product_is_refresh
          ? _c(
              "button",
              {
                staticClass: "btn main-btn pull-right",
                on: { click: _vm.get_products_data },
              },
              [_vm._v("Refresh (" + _vm._s(_vm.product_reset_id) + ")")]
            )
          : _vm._e(),
        _vm._v(" "),
        _vm.product_is_refresh
          ? _c("span", { staticClass: "badge badge-primare mb-1 pull-right" }, [
              _vm._v("Updating..."),
            ])
          : _vm._e(),
      ]),
    ]),
    _vm._v(" "),
    _c(
      "ul",
      { staticClass: "row list-group" },
      _vm._l(_vm.products, function (product) {
        return _c("li", { key: product.id, staticClass: "list-group-item" }, [
          _c("div", { staticClass: "row" }, [
            _c("div", { staticClass: "col-xs-2 col-md-2" }, [
              _c("img", {
                staticClass: "img-circle img-responsive product_table_img",
                attrs: {
                  src: "/public/images/shop_img/" + product.image_1,
                  alt: product.url_title,
                },
              }),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-xs-10 col-md-10" }, [
              _c("div", { staticClass: "row" }, [
                _c("a", { attrs: { href: "#" } }, [
                  _c("h3", [_vm._v(" " + _vm._s(product.title) + " ")]),
                ]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "row" }, [
                _c("h3", [
                  _vm._v(
                    _vm._s(product.price) + " " + _vm._s(product.currency)
                  ),
                ]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "row" }, [
                _c("h3", [_vm._v("Category - " + _vm._s(product.category))]),
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "action" }, [
                _c("div", { staticStyle: { float: "right" } }, [
                  _c(
                    "a",
                    {
                      staticClass: "btn btn-primary btn-xs",
                      attrs: {
                        type: "button",
                        href: "product_edit/" + product.id,
                      },
                    },
                    [_c("span", { staticClass: "glyphicon glyphicon-pencil" })]
                  ),
                  _vm._v(" "),
                  _c(
                    "form",
                    {
                      attrs: { method: "post" },
                      on: {
                        submit: function ($event) {
                          $event.preventDefault()
                          return _vm.product_del(product.id)
                        },
                      },
                    },
                    [
                      _c("input", {
                        attrs: { type: "hidden", name: "_token" },
                      }),
                      _vm._v(" "),
                      _vm._m(0, true),
                    ]
                  ),
                ]),
              ]),
            ]),
          ]),
        ])
      }),
      0
    ),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "form-group" }, [
      _c(
        "button",
        {
          staticClass: "btn btn-danger",
          attrs: {
            type: "submit",
            onclick:
              "return confirm('Are you sure you want to delete this item')",
          },
        },
        [_vm._v("Delete")]
      ),
    ])
  },
]
render._withStripped = true



/***/ })

}]);
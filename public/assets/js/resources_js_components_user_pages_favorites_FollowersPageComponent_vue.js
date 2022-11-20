"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_favorites_FollowersPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************************************************************************************/
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

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    tabsComponent: _items_data_tabs_DataTab_TabsComponent__WEBPACK_IMPORTED_MODULE_0__["default"] // leftMenu,

  },
  data: function data() {
    return {
      data_for_tab: []
    };
  },
  mounted: function mounted() {
    this.get_folowing();
    this.get_folowers();
  },
  watch: {
    '$route': function $route(to, from) {
      this.data_for_tab = [], this.get_folowing();
      this.get_folowers();
      window.scrollTo(0, 0);
    }
  },
  methods: {
    get_folowing: function get_folowing() {
      var _this = this;

      axios.get("../api/following_users_list/").then(function (response) {
        _this.data_for_tab.push({
          'id': 1,
          'data': response.data,
          'table_name': 'Folowing users',
          'table_category': '',
          'table_del_url': 'del_url',
          'table_edit_url': 'edit_url'
        });
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_folowers: function get_folowers() {
      var _this2 = this;

      axios.get("../api/followers_list/").then(function (response) {
        _this2.data_for_tab.push({
          'id': 2,
          'data': response.data,
          'table_name': 'Folowers',
          'table_category': '',
          'table_del_url': 'del_url',
          'table_edit_url': 'edit_url'
        });
      })["catch"](function (error) {
        return console.log(error);
      });
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FollowersPageComponent.vue":
/*!*********************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FollowersPageComponent.vue ***!
  \*********************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./FollowersPageComponent.vue?vue&type=template&id=f60eeab6& */ "./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6&");
/* harmony import */ var _FollowersPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./FollowersPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _FollowersPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__.render,
  _FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/favorites/FollowersPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FollowersPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FollowersPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_FollowersPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6& ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_FollowersPageComponent_vue_vue_type_template_id_f60eeab6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./FollowersPageComponent.vue?vue&type=template&id=f60eeab6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6&":
/*!*******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/favorites/FollowersPageComponent.vue?vue&type=template&id=f60eeab6& ***!
  \*******************************************************************************************************************************************************************************************************************************************************/
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
        [_c("tabsComponent", { attrs: { table_data: this.data_for_tab } })],
        1
      ),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);
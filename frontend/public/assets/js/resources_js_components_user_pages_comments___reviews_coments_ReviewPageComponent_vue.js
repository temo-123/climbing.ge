"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_comments___reviews_coments_ReviewPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../items/data_tabs/DataTab/TabsComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    tabsComponent: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../items/data_tabs/DataTab/TabsComponent'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    breadcrumb: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())
  },
  data: function data() {
    return {
      data_for_tab: []
    };
  },
  mounted: function mounted() {
    this.get_my_guide_comments_data();
  },
  methods: {
    get_my_guide_comments_data: function get_my_guide_comments_data() {
      var _this = this;
      axios.get("../api/get_my_comment/").then(function (response) {
        _this.data_for_tab.push({
          'id': 1,
          'data': response.data,
          'table_name': 'My goudbook comments',
          // 'table_category': this.$route.params.article_category, 
          'table_del_url': 'del_url',
          'table_edit_url': 'edit_url'
        });
        _this.get_my_products_comments_data();
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_my_products_comments_data: function get_my_products_comments_data() {
      var _this2 = this;
      axios.get("../api/get_my_comment/").then(function (response) {
        _this2.data_for_tab.push({
          'id': 1,
          'data': response.data,
          'table_name': 'My products comments',
          // 'table_category': this.$route.params.article_category, 
          'table_del_url': 'del_url',
          'table_edit_url': 'edit_url'
        });
        // this.get_categories_data()
      })["catch"](function (error) {
        return console.log(error);
      });
    }

    // del_comment(itemId) {
    //     axios
    //     .post('/reviews_and_comments/delete_comment/' + itemId, {
    //         comment_id: itemId,
    //     })
    //     .then(Response => {
    //         console.log(response)
    //         this.get_all_comments_data()
    //     })
    //     .catch(
    //         error => console.log(error)
    //     )
    // },
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c&":
/*!***************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c& ***!
  \***************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "row"
  }, [_c("left-menu"), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("breadcrumb")], 1)]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-sm-12"
  }, [_c("tabsComponent", {
    attrs: {
      table_data: this.data_for_tab
    }
  })], 1)])])], 1);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ReviewPageComponent.vue?vue&type=template&id=655bfe0c& */ "./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c&");
/* harmony import */ var _ReviewPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ReviewPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ReviewPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__.render,
  _ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ReviewPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ReviewPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ReviewPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c&":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c& ***!
  \******************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ReviewPageComponent_vue_vue_type_template_id_655bfe0c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./ReviewPageComponent.vue?vue&type=template&id=655bfe0c& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/comments_&_reviews/coments/ReviewPageComponent.vue?vue&type=template&id=655bfe0c&");


/***/ })

}]);
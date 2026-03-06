"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_dashboards_guide_WorkerTaskPanelPageComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    //
  },
  data: function data() {
    return {
      workers: []
    };
  },
  mounted: function mounted() {
    this.get_workers();
  },
  methods: {
    get_workers: function get_workers() {
      var _this = this;
      axios.get("/user/get_worker_users/").then(function (response) {
        _this.workers = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    check_user_task_list: function check_user_task_list() {
      alert('Worker task list show function comming soon');
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, [_c("table", {
    staticClass: "table table-hover",
    attrs: {
      id: "dev-table"
    }
  }, [_vm._m(0), _vm._v(" "), _c("tbody", _vm._l(_vm.workers, function (worker) {
    return _c("tr", {
      key: worker.id
    }, [_vm._m(1, true), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(worker.id))]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", {
      staticStyle: {
        cursor: "zoom-in"
      },
      attrs: {
        title: "Click for quick wiev"
      },
      on: {
        click: function click($event) {
          return _vm.check_user_task_list(worker.id);
        }
      }
    }, [_vm._v("\n                    " + _vm._s(worker.name) + " " + _vm._s(worker.surmane) + "\n                ")]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v(_vm._s(worker.email))]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_vm._v("?")])]);
  }), 0)])]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("thead", [_c("tr", [_c("th", {
    staticStyle: {
      "text-align": "center"
    }
  }, [_c("input", {
    staticClass: "all",
    attrs: {
      type: "checkbox"
    }
  })]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("User ID")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("User name")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("Email")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("Task count")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("td", {
    staticStyle: {
      "text-align": "center"
    }
  }, [_c("input", {
    attrs: {
      type: "checkbox"
    }
  })]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue":
/*!**********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue ***!
  \**********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c& */ "./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c&");
/* harmony import */ var _WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__.render,
  _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c&":
/*!*****************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c& ***!
  \*****************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3d62588c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3d62588c&");


/***/ })

}]);
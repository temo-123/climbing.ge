"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_dashboards_guide_guide_task_panel_WorkerTaskPanelPageCompo-867204"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/modal/task/task/TaskStatusModalComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());
Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/modal/task/task/ShowTaskModalComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }());



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    breadcrumb: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/BreadcrumbComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    showTaskModal: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/modal/task/task/ShowTaskModalComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }()),
    taskStatusModal: Object(function webpackMissingModule() { var e = new Error("Cannot find module '../items/modal/task/task/TaskStatusModalComponent.vue'"); e.code = 'MODULE_NOT_FOUND'; throw e; }())
  },
  data: function data() {
    return {
      tab_num: 1,
      tasks: []
    };
  },
  mounted: function mounted() {
    this.get_user_tasks();
  },
  methods: {
    show_task_status_model: function show_task_status_model(task_id) {
      this.$refs.task_status_modal.show_modal(task_id);
    },
    show_task_modal: function show_task_modal(task_id) {
      this.$refs.show_task_modal.show_modal(task_id);
    },
    get_user_tasks: function get_user_tasks() {
      var _this = this;
      axios.get("/task/get_user_tasks/").then(function (response) {
        _this.tasks = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    task_status_color: function task_status_color(status) {
      if (status == 'in_process') {
        return 'alert-warning';
      } else if (status == 'finished') {
        return 'alert-success';
      } else if (status == 'confirmation_completion') {
        return 'alert-info';
      } else {
        return 'alert-danger';
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "col-sm-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("breadcrumb")], 1)]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "tabs"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.tab_num,
      expression: "tab_num"
    }],
    attrs: {
      type: "radio",
      id: "1"
    },
    domProps: {
      value: 1,
      checked: _vm._q(_vm.tab_num, 1)
    },
    on: {
      change: function change($event) {
        _vm.tab_num = 1;
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": "1"
    }
  }, [_vm._v("Tasks")])])])])]), _vm._v(" "), _vm.tab_num == 1 ? _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12 mb-2"
  }, [_c("button", {
    staticClass: "btn btn-success pull-right",
    on: {
      click: _vm.get_user_tasks
    }
  }, [_vm._v("Refresh")])]), _vm._v(" "), _vm._l(_vm.tasks, function (task) {
    return _c("div", {
      staticClass: "col-md-12",
      attrs: {
        kay: task.id
      }
    }, [_c("div", {
      "class": "alert " + _vm.task_status_color(task.status),
      attrs: {
        role: "alert"
      }
    }, [_c("div", {
      staticClass: "row"
    }, [_c("div", {
      staticClass: "col-md-12"
    }, [_c("strong", [_vm._v(_vm._s(task.status))]), _vm._v(" " + _vm._s(task.title) + "\n                                        "), _c("p", [_vm._v(_vm._s(task.deadline))])]), _vm._v(" "), _c("div", {
      staticClass: "col-md-12"
    }, [_c("button", {
      staticClass: "btn btn-success float-right",
      on: {
        click: function click($event) {
          return _vm.show_task_modal(task.id);
        }
      }
    }, [_vm._v("Show detals")]), _vm._v(" "), task.status != "confirmation_completion" && task.status != "finished" ? _c("button", {
      staticClass: "btn btn-primary float-right",
      on: {
        click: function click($event) {
          return _vm.show_task_status_model(task.id);
        }
      }
    }, [_vm._v("Update status")]) : _vm._e()])])])]);
  })], 2) : _vm._e()])])])]), _vm._v(" "), _c("taskStatusModal", {
    ref: "task_status_modal",
    on: {
      restart: _vm.get_user_tasks
    }
  }), _vm._v(" "), _c("showTaskModal", {
    ref: "show_task_modal"
  })], 1);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true& */ "./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true&");
/* harmony import */ var _WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render,
  _WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  "3c2a1a80",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true&":
/*!**********************************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true& ***!
  \**********************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_WorkerTaskPanelPageComponent_vue_vue_type_template_id_3c2a1a80_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/dashboards/guide/guide_task_panel/WorkerTaskPanelPageComponent.vue?vue&type=template&id=3c2a1a80&scoped=true&");


/***/ })

}]);
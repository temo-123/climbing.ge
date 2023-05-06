"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_siteInfo_GeneralInfo_GlobalInfoAddFormComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  props: ['back_url'],
  data: function data() {
    return {
      data: {
        title: '',
        us_text: '',
        ru_text: '',
        ka_text: ''
      },
      errors: []
    };
  },
  mounted: function mounted() {},
  methods: {
    add_global_info: function add_global_info() {
      var _this = this;
      axios.post('../../api/general_info/', {
        data: this.data,
        _method: 'POST'
      }).then(function (response) {
        _this.go_back();
      })["catch"](function (error) {
        // if (error.response.status == 422) {
        //     this.errors = error.response.data.errors
        // }
        // else{
        //     this.errors = error.response.data
        // }
        console.log(error);
      });
      // .record(errors) {
      //     this.errors = errors.response.data.errors;
      // }
    },
    go_back: function go_back() {
      this.$router.go(-1);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "col_md_12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      type: "submit",
      form: "edit_genral_info_form"
    }
  }, [_vm._v("Save")]), _vm._v(" "), _c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      type: "submit"
    },
    on: {
      click: function click($event) {
        return _vm.go_back();
      }
    }
  }, [_vm._v("Go back")])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "tabs"
  }, [_c("input", {
    attrs: {
      type: "radio",
      name: "tabs",
      id: "1",
      checked: "checked"
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": "1"
    }
  }, [_vm._v("Global info")]), _vm._v(" "), _c("div", {
    staticClass: "tab"
  }, [_vm._m(0), _vm._v(" "), _c("form", {
    staticClass: "form",
    attrs: {
      id: "edit_genral_info_form"
    },
    on: {
      submit: function submit($event) {
        $event.preventDefault();
        return _vm.add_global_info.apply(null, arguments);
      }
    }
  }, [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.title,
      expression: "data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "us_name"
    },
    domProps: {
      value: _vm.data.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" English name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: this.$editorConfig
    },
    model: {
      value: _vm.data.us_text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "us_text", $$v);
      },
      expression: "data.us_text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Georgian name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: this.$editorConfig
    },
    model: {
      value: _vm.data.ka_text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "ka_text", $$v);
      },
      expression: "data.ka_text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Russian name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: this.$editorConfig
    },
    model: {
      value: _vm.data.ru_text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "ru_text", $$v);
      },
      expression: "data.ru_text"
    }
  })], 1)])])])])])]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Add Global info Category")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Add information for using this info in many articles.")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue":
/*!************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue ***!
  \************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8& */ "./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8&");
/* harmony import */ var _GlobalInfoAddFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GlobalInfoAddFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _GlobalInfoAddFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__.render,
  _GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoAddFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoAddFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoAddFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8&":
/*!*******************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8& ***!
  \*******************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoAddFormComponent_vue_vue_type_template_id_5f9431f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/siteInfo/GeneralInfo/GlobalInfoAddFormComponent.vue?vue&type=template&id=5f9431f8&");


/***/ })

}]);
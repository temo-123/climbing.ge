"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_routes_and_sectors_MTP_MTPEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  data: function data() {
    return {
      description_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
      regions: [],
      sectors: [],
      errors: [],
      data: {
        article_id: "",
        sector_id: "",
        name: "",
        text: "",
        height: "",
        first_ascent: "",
        author: ''
      },
      is_loading: false,
      is_back_action_query: true,
      go_back_action: false
    };
  },
  mounted: function mounted() {
    this.get_region_data();
  },
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (this.is_back_action_query == true) {
      if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        this.is_back_action_query = false;
        next();
      } else {
        next(false);
      }
    } else {
      next();
    }
  },
  methods: {
    get_region_data: function get_region_data() {
      var _this = this;
      axios.post("../../api/article/", {
        category: 'outdoor'
      }).then(function (response) {
        _this.regions = response.data;
        _this.get_sectors_data();
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this.is_loading = false;
      });
    },
    get_sectors_data: function get_sectors_data() {
      var _this2 = this;
      axios.get("../../api/sector/").then(function (response) {
        _this2.all_sectors = response.data;
        _this2.get_mtp_editing_data();
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this2.is_loading = false;
      });
    },
    get_mtp_editing_data: function get_mtp_editing_data() {
      var _this3 = this;
      this.is_loading = true;
      axios.get("../../api/mtp/get_editing_mtp/" + this.$route.params.id).then(function (response) {
        _this3.data = response.data;
        var sector = _this3.all_sectors.find(function (item) {
          return item.id === _this3.data.sector_id;
        });
        var action_article = _this3.regions.find(function (item) {
          return item.id === sector.article_id;
        });
        _this3.data.article_id = action_article.id;
        _this3.filter_sectors();
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this3.is_loading = false;
      });
    },
    filter_sectors: function filter_sectors() {
      var vm = this;
      this.sectors = this.all_sectors.filter(function (item) {
        return item.article_id == vm.data.article_id;
      });
    },
    edit_mtp: function edit_mtp() {
      var _this4 = this;
      axios.post('../../api/mtp/mtp_edit/' + this.$route.params.id, {
        data: this.data
      }).then(function (response) {
        _this4.go_back(true);
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this4.errors = error.response.data.errors;
        }
      })["finally"](function () {
        return _this4.is_loading = false;
      });
    },
    go_back: function go_back() {
      var back_action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.is_back_action_query = this.$going.back(this, back_action);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, [_vm.is_loading ? _c("div", {
    staticClass: "row justify-content-center"
  }, [_c("div", {
    staticClass: "col-md-4"
  }, [_c("img", {
    attrs: {
      src: "../../../../../../public/images/site_img/loading.gif",
      alt: "loading"
    }
  })])]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      type: "submit"
    },
    on: {
      click: function click($event) {
        return _vm.go_back();
      }
    }
  }, [_vm._v("Beck")])])]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "row"
  }, [_vm._m(0)]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "wrapper container-fluid container"
  }, [_c("form", {
    attrs: {
      id: "route_edit_form"
    },
    on: {
      submit: function submit($event) {
        $event.preventDefault();
        return _vm.edit_mtp();
      }
    }
  }, [_c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Region ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.article_id,
      expression: "data.article_id"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      click: function click($event) {
        return _vm.filter_sectors();
      },
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "article_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Select outdoor article")]), _vm._v(" "), _vm._l(_vm.regions, function (region) {
    return _c("option", {
      key: region.id,
      domProps: {
        value: region.id
      }
    }, [_vm._v(_vm._s(region.url_title))]);
  })], 2)]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_vm.data.article_id != "" ? _c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.sector_id,
      expression: "data.sector_id"
    }],
    staticClass: "form-control",
    attrs: {
      required: ""
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data, "sector_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "",
      disabled: ""
    }
  }, [_vm._v("Select sector")]), _vm._v(" "), _vm._l(_vm.sectors, function (sector) {
    return _c("option", {
      key: sector.id,
      domProps: {
        value: sector.id
      }
    }, [_vm._v(_vm._s(sector.name))]);
  })], 2) : _vm._e()])]), _vm._v(" "), _vm.errors.sector_id ? _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n              " + _vm._s(_vm.errors.sector_id[0]) + "\n            ")])])]) : _vm._e(), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Multy pitch name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.name,
      expression: "data.name"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name",
      placeholder: "Name",
      required: ""
    },
    domProps: {
      value: _vm.data.name
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "name", $event.target.value);
      }
    }
  }), _vm._v(" "), _vm.errors.name ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n              " + _vm._s(_vm.errors.name[0]) + "\n            ")]) : _vm._e()])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.description_editor
    },
    model: {
      value: _vm.data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "text", $$v);
      },
      expression: "data.text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Height ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-9"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.height,
      expression: "data.height"
    }],
    staticClass: "form-control",
    attrs: {
      type: "number",
      name: "title",
      placeholder: "Height"
    },
    domProps: {
      value: _vm.data.height
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "height", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("label", {
    staticClass: "col-md-1 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" M ")])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v("First ascent / Auther")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.first_ascent,
      expression: "data.first_ascent"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "first_ascent",
      placeholder: "First ascent"
    },
    domProps: {
      value: _vm.data.first_ascent
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "first_ascent", $event.target.value);
      }
    }
  })]), _vm._v(" "), _c("div", {
    staticClass: "col-md-5"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.author,
      expression: "data.author"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "author",
      placeholder: "Auther"
    },
    domProps: {
      value: _vm.data.author
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data, "author", $event.target.value);
      }
    }
  })])])])]) : _vm._e(), _vm._v(" "), _vm.is_loading ? _c("div", {
    staticClass: "row justify-content-center"
  }, [_c("div", {
    staticClass: "col-md-4"
  }, [_c("img", {
    attrs: {
      src: "../../../../../../public/images/site_img/loading.gif",
      alt: "loading"
    }
  })])]) : _vm._e()]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      form: "route_edit_form",
      type: "submit"
    }
  }, [_vm._v("Save and go back")]), _vm._v(" "), _c("p", [_vm._v("Save and go to route tab page")])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue":
/*!****************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue ***!
  \****************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MTPEditComponent.vue?vue&type=template&id=2f8ee88b& */ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b&");
/* harmony import */ var _MTPEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MTPEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MTPEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__.render,
  _MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MTPEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b&":
/*!***********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b& ***!
  \***********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPEditComponent_vue_vue_type_template_id_2f8ee88b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MTPEditComponent.vue?vue&type=template&id=2f8ee88b& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPEditComponent.vue?vue&type=template&id=2f8ee88b&");


/***/ })

}]);
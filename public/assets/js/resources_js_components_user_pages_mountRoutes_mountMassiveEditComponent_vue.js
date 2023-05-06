"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_mountRoutes_mountMassiveEditComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  props: [
    // 'editing_mount_id',
    // 'back_url'
  ],
  data: function data() {
    return {
      data: {
        us_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        ru_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        ka_data: {
          title: '',
          short_description: '',
          text: '',
          route: '',
          how_get: '',
          best_time: ''
        },
        global_data: {
          demo_image: "",
          map: "",
          weather: ""
        }
      },
      editor_config: {
        us_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        us_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        us_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        us_how_get_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        us_best_time_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ka_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_how_get_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_best_time_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ru_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_how_get_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_best_time_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config()
      },
      is_loading: false,
      tab_num: 1,
      errors: [],
      is_back_action: false
    };
  },
  mounted: function mounted() {
    this.get_editing_data();
  },
  beforeRouteLeave: function beforeRouteLeave(to, from, next) {
    if (this.is_back_action == true) {
      if (window.confirm('Added information will be deleted!!! Are you sure, you want go back?')) {
        this.is_back_action = false;
        next();
      } else {
        next(false);
      }
    } else {
      next();
    }
  },
  // created() {
  //     // so nice
  //     // console.log(`${this.$going.back(this.is_back_action, this, true)} is currently logged in.`);
  //     this.$test()
  // },
  methods: {
    edit_mount: function edit_mount() {
      var _this = this;
      axios.post('/mount/edit_mount_massive/' + this.$route.params.id, {
        data: this.data
      }).then(function (Response) {
        _this.go_back(true);
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this.errors = error.response.data.validation;
        }
        // else {
        //     alert(error)
        // }
      });
    },

    get_editing_data: function get_editing_data() {
      var _this2 = this;
      axios.get('/mount/get_editing_mount_data/' + this.$route.params.id).then(function (response) {
        _this2.data = response.data;
      });
      // .catch(
      //     error => alert(error)
      // );
    },

    go_back: function go_back() {
      var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.is_back_action = this.$going.back(this, action);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8&":
/*!******************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8& ***!
  \******************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    staticClass: "tabs"
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
  }, [_c("div", {
    staticClass: "form-group"
  }, [_c("button", {
    staticClass: "btn btn-primary",
    attrs: {
      type: "submit"
    },
    on: {
      click: function click($event) {
        return _vm.edit_mount();
      }
    }
  }, [_vm._v("Save")])])]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
    staticClass: "row"
  }, [_vm.errors.length != 0 ? _c("div", {
    staticClass: "col-md-12"
  }, [_vm.errors.global_info_validation.name ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Demo name - " + _vm._s(_vm.errors.global_info_validation.name[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.global_info_validation.us_title_for_url_title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Us title - " + _vm._s(_vm.errors.global_info_validation.us_title_for_url_title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.us_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English title - " + _vm._s(_vm.errors.us_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.us_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English description - " + _vm._s(_vm.errors.us_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.us_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English text - " + _vm._s(_vm.errors.us_info_validation.text[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ka_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian title - " + _vm._s(_vm.errors.ka_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ka_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian description - " + _vm._s(_vm.errors.ka_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ka_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian text - " + _vm._s(_vm.errors.ka_info_validation.text[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ru_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russion title - " + _vm._s(_vm.errors.ru_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ru_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russiondescription - " + _vm._s(_vm.errors.ru_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.errors.ru_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russion text - " + _vm._s(_vm.errors.ru_info_validation.text[0]) + "\n            ")]) : _vm._e()]) : _vm._e()]) : _vm._e(), _vm._v(" "), !_vm.is_loading ? _c("div", {
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
  }, [_vm._v("Global info")])]), _vm._v(" "), _c("div", {
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
      id: "2"
    },
    domProps: {
      value: 2,
      checked: _vm._q(_vm.tab_num, 2)
    },
    on: {
      change: function change($event) {
        _vm.tab_num = 2;
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": "2"
    }
  }, [_vm._v("English text")])]), _vm._v(" "), _c("div", {
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
      id: "4"
    },
    domProps: {
      value: 4,
      checked: _vm._q(_vm.tab_num, 4)
    },
    on: {
      change: function change($event) {
        _vm.tab_num = 4;
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": "4"
    }
  }, [_vm._v("Georgian text")])]), _vm._v(" "), _c("div", {
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
      id: "3"
    },
    domProps: {
      value: 3,
      checked: _vm._q(_vm.tab_num, 3)
    },
    on: {
      change: function change($event) {
        _vm.tab_num = 3;
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": "3"
    }
  }, [_vm._v("Russion text")])])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 1,
      expression: "tab_num == 1"
    }],
    staticClass: "row width_100"
  }, [_vm._m(0), _vm._v(" "), _c("form", [_c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Demo name ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_data.name,
      expression: "data.global_data.name"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "demo name"
    },
    domProps: {
      value: _vm.data.global_data.name
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_data, "name", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Map ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_data.map,
      expression: "data.global_data.map"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "map"
    },
    domProps: {
      value: _vm.data.global_data.map
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_data, "map", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Weather ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_data.weather,
      expression: "data.global_data.weather"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "weather"
    },
    domProps: {
      value: _vm.data.global_data.weather
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_data, "weather", $event.target.value);
      }
    }
  })])])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 2,
      expression: "tab_num == 2"
    }],
    staticClass: "row"
  }, [_vm._m(1), _vm._v(" "), _c("form", [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title english ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.us_data.title,
      expression: "data.us_data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.us_data.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.us_data, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("hr"), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" English text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.us_text_editor_config
    },
    model: {
      value: _vm.data.us_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_data, "text", $$v);
      },
      expression: "data.us_data.text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" English description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.us_short_description_text_editor
    },
    model: {
      value: _vm.data.us_data.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_data, "short_description", $$v);
      },
      expression: "data.us_data.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" English how get hear ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.us_how_get_editor_config
    },
    model: {
      value: _vm.data.us_data.how_get,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_data, "how_get", $$v);
      },
      expression: "data.us_data.how_get"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" English best time ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.us_best_time_editor_config
    },
    model: {
      value: _vm.data.us_data.best_time,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_data, "best_time", $$v);
      },
      expression: "data.us_data.best_time"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 3,
      expression: "tab_num == 3"
    }],
    staticClass: "row"
  }, [_vm._m(2), _vm._v(" "), _c("form", [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title rusian ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.ru_data.title,
      expression: "data.ru_data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name_ru"
    },
    domProps: {
      value: _vm.data.ru_data.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ru_data, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("hr"), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Rusian text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ru_text_editor_config
    },
    model: {
      value: _vm.data.ru_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_data, "text", $$v);
      },
      expression: "data.ru_data.text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Rusian description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ru_short_description_text_editor
    },
    model: {
      value: _vm.data.ru_data.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_data, "short_description", $$v);
      },
      expression: "data.ru_data.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Rusian how get hear ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ru_how_get_editor_config
    },
    model: {
      value: _vm.data.ru_data.how_get,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_data, "how_get", $$v);
      },
      expression: "data.ru_data.how_get"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Rusian best time ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ru_best_time_editor_config
    },
    model: {
      value: _vm.data.ru_data.best_time,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_data, "best_time", $$v);
      },
      expression: "data.ru_data.best_time"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 4,
      expression: "tab_num == 4"
    }],
    staticClass: "row"
  }, [_vm._m(3), _vm._v(" "), _c("form", [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title georgian ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.ka_data.title,
      expression: "data.ka_data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name_ka"
    },
    domProps: {
      value: _vm.data.ka_data.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ka_data, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("hr"), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Georgian text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ka_text_editor_config
    },
    model: {
      value: _vm.data.ka_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_data, "text", $$v);
      },
      expression: "data.ka_data.text"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Georgian description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ka_short_description_text_editor
    },
    model: {
      value: _vm.data.ka_data.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_data, "short_description", $$v);
      },
      expression: "data.ka_data.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Georgian how get hear ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ka_how_get_editor_config
    },
    model: {
      value: _vm.data.ka_data.how_get,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_data, "how_get", $$v);
      },
      expression: "data.ka_data.how_get"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Georgian best time ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ka_best_time_editor_config
    },
    model: {
      value: _vm.data.ka_data.best_time,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_data, "best_time", $$v);
      },
      expression: "data.ka_data.best_time"
    }
  })], 1)])])])])]) : _vm._e()]);
};
var staticRenderFns = [function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "jumbotron width_100"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_c("span", [_vm._v("Mount masive global information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Mount masive global information.")])])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "jumbotron width_100"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_c("span", [_vm._v("Mount masive English information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Mount masive English information.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "jumbotron width_100"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_c("span", [_vm._v("Mount masive Georgian information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Mount masive Georgian information.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "jumbotron width_100"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_c("span", [_vm._v("Mount masive Russion information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Mount masive Russion information.")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue ***!
  \**************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8& */ "./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8&");
/* harmony import */ var _mountMassiveEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./mountMassiveEditComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _mountMassiveEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__.render,
  _mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveEditComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveEditComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8&":
/*!*********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8& ***!
  \*********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_mountMassiveEditComponent_vue_vue_type_template_id_2458f7f8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/mountRoutes/mountMassiveEditComponent.vue?vue&type=template&id=2458f7f8&");


/***/ })

}]);
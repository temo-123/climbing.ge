"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_articles_forms_add_forms_LocaleDataFormComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  props: {
    title_prop: {
      type: String,
      "default": "Title error"
    },
    value_name_prop: {
      type: String,
      "default": ""
    },
    form_data_prop: {
      type: String,
      "default": ""
    },
    form_value_name_prop: {
      type: String,
      "default": ""
    },
    block_action_prop: {
      type: String,
      "default": "new_info"
    },
    block_id_prop: {
      type: Number,
      "default": 0
    },
    locale_prop: {
      type: String
    },
    get_data_in_component_prop: {
      type: Boolean,
      "default": true
    },
    global_data_array_prop: {
      type: Array,
      "default": []
    }
  },
  data: function data() {
    return {
      text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
      general_infos: [],
      form_data: this.form_data_prop,
      locale: this.locale_prop,
      form_value_name: this.form_value_name_prop,
      value_name: this.value_name_prop,
      global_blocks: {
        block_action: 'new_info',
        block_id: 0
      }
    };
  },
  mounted: function mounted() {
    if (this.get_data_in_component_prop == true) {
      this.get_general_info();
    }
    this.global_blocks.block_action = this.block_action_prop;
    this.global_blocks.block_id = this.block_id_prop;
    this.general_infos = this.global_data_array_prop;
  },
  watch: {
    // form_data_prop: function(){
    //     this.form_data = this.form_data_prop
    // },
    block_action_prop: function block_action_prop() {
      this.global_blocks.block_action = this.block_action_prop;
    },
    block_id_prop: function block_id_prop() {
      this.global_blocks.block_id = this.block_id_prop;
    },
    global_data_array_prop: function global_data_array_prop() {
      if (this.get_data_in_component_prop == false) {
        this.general_infos = this.global_data_array_prop;
      }
    }
  },
  methods: {
    get_general_info: function get_general_info() {
      var _this = this;
      if (this.get_data_in_component_prop == true) {
        axios.get('../../../api/general_info/').then(function (response) {
          _this.general_infos = response.data;
        })["catch"](function (errors) {
          return console.log(errors);
        });
      }
    },
    input_event: function input_event() {
      this.$emit('get_form_data', {
        locale: this.locale,
        form_data: this.form_data,
        form_value_name: this.form_value_name
      });
    },
    block_action: function block_action(status) {
      this.global_blocks.block_action = status;
      this.$emit('get_global_blocks_status', {
        value_name: this.value_name,
        block_action: this.global_blocks.block_action
      });
    },
    block_id: function block_id() {
      this.$emit('get_global_blocks_id', {
        value_name: this.value_name,
        block_id: this.global_blocks.block_id
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @innologica/vue-stackable-modal */ "./node_modules/@innologica/vue-stackable-modal/dist/vue-stackable-modal.umd.min.js");
/* harmony import */ var _innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _items_GlobalInfoFormBlockComponent_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../items/GlobalInfoFormBlockComponent.vue */ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue");
// import { SlickList, SlickItem } from 'vue-slicksort';
 //https://innologica.github.io/vue-stackable-modal/#sample-css


// import { editor_config } from '../../../../../../mixins/editor/editor_config_mixin.js'

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    StackModal: (_innologica_vue_stackable_modal__WEBPACK_IMPORTED_MODULE_0___default()),
    GlobalInfoFormBlock: _items_GlobalInfoFormBlockComponent_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
    // SlickItem,
    // SlickList,
  },

  mixins: [
    // editor_config
  ],
  props: [
  // 'info_block_prop',
  // 'routes_info_prop',
  // 'what_need_info_prop',
  // 'best_time_prop',

  'global_blocks_prop', 'title', 'description'],
  data: function data() {
    return {
      // category: 'this.$route.params.article_category',
      category: this.$route.params.article_category,
      errors: [],
      error: [],
      editor_config: {
        short_description_text: this.$editor_config.get_small_editor_config(),
        text: this.$editor_config.get_big_editor_config(),
        how_get: this.$editor_config.get_big_editor_config(),
        price_text: this.$editor_config.get_big_editor_config()
      },
      data: {
        title: '',
        short_description: '',
        text: '',
        route: '',
        how_get: '',
        best_time: '',
        what_need: '',
        info: '',
        time: ''
      },
      general_infos: [],
      global_blocks: {
        info_block: '',
        routes_info: '',
        what_need_info: '',
        best_time: '',
        info_block_id: 0,
        routes_info_id: 0,
        what_need_info_id: 0,
        best_time_id: 0
      }
    };
  },
  mounted: function mounted() {
    this.global_blocks = this.global_blocks_prop;
    // this.routes_info = this.routes_info_prop
    // this.what_need_info = this.what_need_info_prop
    // this.best_time = this.best_time_prop

    this.get_general_info();
    this.$emit('locale_form_data', this.data);
  },
  watch: {
    global_block: function global_block() {
      this.global_blocks = this.global_blocks_prop;
    }
    // routes_info_prop: function(){
    //     this.routes_info = this.routes_info_prop
    // },
    // what_need_info_prop: function(){
    //     this.what_need_info_prop = this.what_need_info_prop
    // },
    // best_time_prop: function(){
    //     this.best_time = this.best_time_prop
    // }
  },

  methods: {
    uploader: function uploader(editor) {
      editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
        return new UploadAdapter(loader);
      };
    },
    get_general_info: function get_general_info() {
      var _this = this;
      axios.get('../../../api/general_info/').then(function (response) {
        _this.general_infos = response.data;
      })["catch"](function (errors) {
        return console.log(errors);
      });
    },
    info_block_action: function info_block_action(status) {
      this.global_blocks.info_block = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    best_time_action: function best_time_action(status) {
      this.global_blocks.best_time = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    routes_action: function routes_action(status) {
      this.global_blocks.routes_info = status;
      this.$emit('global_blocks', this.global_blocks);
    },
    what_need_block_action: function what_need_block_action(status) {
      this.global_blocks.what_need_info = status;
      this.$emit('global_blocks', this.global_blocks);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, [_vm.general_infos.length ? _c("div", [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-3 genera_block_action_but"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_action,
      expression: "global_blocks.block_action"
    }],
    attrs: {
      type: "radio",
      id: _vm.locale + "_" + _vm.value_name + "_new_info",
      name: "fav_language",
      value: "new_info"
    },
    domProps: {
      checked: _vm._q(_vm.global_blocks.block_action, "new_info")
    },
    on: {
      click: function click($event) {
        return _vm.block_action("new_info");
      },
      change: function change($event) {
        return _vm.$set(_vm.global_blocks, "block_action", "new_info");
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": _vm.locale + "_" + _vm.value_name + "_new_info"
    }
  }, [_vm._v("New info")]), _c("br")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-3 genera_block_action_but"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_action,
      expression: "global_blocks.block_action"
    }],
    attrs: {
      type: "radio",
      id: _vm.locale + "_" + _vm.value_name + "_befor",
      name: "fav_language",
      value: "befor"
    },
    domProps: {
      checked: _vm._q(_vm.global_blocks.block_action, "befor")
    },
    on: {
      click: function click($event) {
        return _vm.block_action("befor");
      },
      change: function change($event) {
        return _vm.$set(_vm.global_blocks, "block_action", "befor");
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": _vm.locale + "_" + _vm.value_name + "_befor"
    }
  }, [_vm._v("Befor")]), _c("br")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-3 genera_block_action_but"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_action,
      expression: "global_blocks.block_action"
    }],
    attrs: {
      type: "radio",
      id: _vm.locale + "_" + _vm.value_name + "_after",
      name: "fav_language",
      value: "after"
    },
    domProps: {
      checked: _vm._q(_vm.global_blocks.block_action, "after")
    },
    on: {
      click: function click($event) {
        return _vm.block_action("after");
      },
      change: function change($event) {
        return _vm.$set(_vm.global_blocks, "block_action", "after");
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": _vm.locale + "_" + _vm.value_name + "_after"
    }
  }, [_vm._v("After")]), _c("br")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-3 genera_block_action_but"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_action,
      expression: "global_blocks.block_action"
    }],
    attrs: {
      type: "radio",
      id: _vm.locale + "_" + _vm.value_name + "_instead",
      name: "fav_language",
      value: "instead"
    },
    domProps: {
      checked: _vm._q(_vm.global_blocks.block_action, "instead")
    },
    on: {
      click: function click($event) {
        return _vm.block_action("instead");
      },
      change: function change($event) {
        return _vm.$set(_vm.global_blocks, "block_action", "instead");
      }
    }
  }), _vm._v(" "), _c("label", {
    attrs: {
      "for": _vm.locale + "_" + _vm.value_name + "_instead"
    }
  }, [_vm._v("Instead")])])])]) : _vm._e(), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" " + _vm._s(_vm.title_prop) + " ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_vm.global_blocks.block_action == "befor" ? _c("div", {
    staticClass: "col-md-12"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_id,
      expression: "global_blocks.block_id"
    }],
    staticClass: "form-control",
    on: {
      change: [function ($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.global_blocks, "block_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }, function ($event) {
        return _vm.block_id();
      }]
    }
  }, _vm._l(_vm.general_infos, function (general_info) {
    return _c("option", {
      key: general_info.id,
      domProps: {
        value: general_info.id
      }
    }, [_vm._v(_vm._s(general_info.title))]);
  }), 0)]) : _vm._e(), _vm._v(" "), _vm.global_blocks.block_action == "befor" || _vm.global_blocks.block_action == "after" || _vm.global_blocks.block_action == "new_info" ? _c("div", {
    staticClass: "col-md-12"
  }, [_c("ckeditor", {
    attrs: {
      config: this.text_editor_config
    },
    on: {
      input: _vm.input_event
    },
    model: {
      value: _vm.form_data,
      callback: function callback($$v) {
        _vm.form_data = $$v;
      },
      expression: "form_data"
    }
  })], 1) : _vm._e(), _vm._v(" "), _vm.global_blocks.block_action == "after" || _vm.global_blocks.block_action == "instead" ? _c("div", {
    staticClass: "col-md-12"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.global_blocks.block_id,
      expression: "global_blocks.block_id"
    }],
    staticClass: "form-control",
    on: {
      change: [function ($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.global_blocks, "block_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }, function ($event) {
        return _vm.block_id();
      }]
    }
  }, _vm._l(_vm.general_infos, function (general_info) {
    return _c("option", {
      key: general_info.id,
      domProps: {
        value: general_info.id
      }
    }, [_vm._v(_vm._s(general_info.title))]);
  }), 0)]) : _vm._e()])])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, [_c("div", {
    staticClass: "jumbotron width_100"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_c("span", {
    staticStyle: {
      "text-transform": "capitalize"
    }
  }, [_vm._v(_vm._s(this.category))]), _vm._v(" " + _vm._s(this.title))]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v(_vm._s(this.description))])])]), _vm._v(" "), _c("div", {
    staticClass: "wrapper container-fluid container"
  }, [_c("form", {
    attrs: {
      method: "POST"
    },
    on: {
      submit: function submit($event) {
        $event.preventDefault();
        return _vm.add_article.apply(null, arguments);
      }
    }
  }, [_c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
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
      name: "name"
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
  }), _vm._v(" "), _vm.errors.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                            " + _vm._s(_vm.errors.title[0]) + "\n                        ")]) : _vm._e()])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Short description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.short_description_text_editor
    },
    model: {
      value: _vm.data.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "short_description", $$v);
      },
      expression: "data.short_description"
    }
  }), _vm._v(" "), _vm.errors.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                            " + _vm._s(_vm.errors.short_description[0]) + "\n                        ")]) : _vm._e()], 1)]), _vm._v(" "), _c("div", {
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
      id: "text",
      editor: "text",
      config: _vm.text_editor_config
    },
    model: {
      value: _vm.data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "text", $$v);
      },
      expression: "data.text"
    }
  })], 1)]), _vm._v(" "), this.category != "mount_route" ? _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" How to get hear ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("ckeditor", {
    attrs: {
      config: this.$editorConfig
    },
    model: {
      value: _vm.data.how_get,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "how_get", $$v);
      },
      expression: "data.how_get"
    }
  })], 1)]) : _vm._e(), _vm._v(" "), this.category == "outdoor" ? _c("hr") : _vm._e(), _vm._v(" "), _c("GlobalInfoFormBlock", {
    attrs: {
      title_prop: "Routes description",
      form_value_name_prop: "route",
      form_data_prop: _vm.data.route,
      locale_prop: _vm.locale_prop,
      block_action_prop: _vm.global_blocks.routes_info,
      block_id_prop: _vm.global_blocks.routes_info_id,
      value_name_prop: "routes_info",
      global_data_array_prop: _vm.general_infos,
      get_data_in_component_prop: false
    },
    on: {
      get_form_data: _vm.get_value_insert_text,
      get_global_blocks_status: _vm.get_global_blocks_status_action,
      get_global_blocks_id: _vm.get_global_blocks_id
    }
  }), _vm._v(" "), _c("GlobalInfoFormBlock", {
    attrs: {
      title_prop: "Best time for climbing",
      form_value_name_prop: "best_time",
      form_data_prop: _vm.data.best_time,
      locale_prop: _vm.locale_prop,
      block_action_prop: _vm.global_blocks.best_time,
      block_id_prop: _vm.global_blocks.best_time_id,
      value_name_prop: "best_time",
      global_data_array_prop: _vm.general_infos,
      get_data_in_component_prop: false
    },
    on: {
      get_form_data: _vm.get_value_insert_text,
      get_global_blocks_status: _vm.get_global_blocks_status_action,
      get_global_blocks_id: _vm.get_global_blocks_id
    }
  }), _vm._v(" "), this.category == "outdoor" || this.category == "ice" || this.category == "mount_route" ? _c("hr") : _vm._e(), _vm._v(" "), _c("GlobalInfoFormBlock", {
    attrs: {
      title_prop: "What you need",
      form_value_name_prop: "what_need",
      form_data_prop: _vm.data.what_need,
      locale_prop: _vm.locale_prop,
      block_action_prop: _vm.global_blocks.what_need_info,
      block_id_prop: _vm.global_blocks.what_need_info_id,
      value_name_prop: "what_need_info",
      global_data_array_prop: _vm.general_infos,
      get_data_in_component_prop: false
    },
    on: {
      get_form_data: _vm.get_value_insert_text,
      get_global_blocks_status: _vm.get_global_blocks_status_action,
      get_global_blocks_id: _vm.get_global_blocks_id
    }
  }), _vm._v(" "), _c("hr"), _vm._v(" "), _c("GlobalInfoFormBlock", {
    attrs: {
      title_prop: "Contact info",
      form_value_name_prop: "info",
      form_data_prop: _vm.data.info,
      locale_prop: _vm.locale_prop,
      block_action_prop: _vm.global_blocks.info_block,
      block_id_prop: _vm.global_blocks.info_block_id,
      value_name_prop: "info_block",
      global_data_array_prop: _vm.general_infos,
      get_data_in_component_prop: false
    },
    on: {
      get_form_data: _vm.get_value_insert_text,
      get_global_blocks_status: _vm.get_global_blocks_status_action,
      get_global_blocks_id: _vm.get_global_blocks_id
    }
  }), _vm._v(" "), this.category == "indoor" ? _c("div", {
    staticClass: "form-group clearfix row"
  }, [_c("label", {
    staticClass: "col-md-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Price description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-md-10"
  }, [_c("ckeditor", {
    attrs: {
      config: this.$editorConfig
    },
    model: {
      value: _vm.data.price_text,
      callback: function callback($$v) {
        _vm.$set(_vm.data, "price_text", $$v);
      },
      expression: "data.price_text"
    }
  })], 1)]) : _vm._e()], 1)])]);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js */ "./node_modules/laravel-mix/node_modules/css-loader/dist/runtime/api.js");
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0__);
// Imports

var ___CSS_LOADER_EXPORT___ = _node_modules_laravel_mix_node_modules_css_loader_dist_runtime_api_js__WEBPACK_IMPORTED_MODULE_0___default()(function(i){return i[1]});
// Module
___CSS_LOADER_EXPORT___.push([module.id, "\n.genera_block_action_but label{\n    background-color: #ffc9c9;\n    border: 1px solid #424242 !important;\n}\n", ""]);
// Exports
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (___CSS_LOADER_EXPORT___);


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&":
/*!***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& ***!
  \***********************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! !../../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_style_index_0_id_e3381806_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& */ "./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&");

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_style_index_0_id_e3381806_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"], options);



/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_style_index_0_id_e3381806_lang_css___WEBPACK_IMPORTED_MODULE_1__["default"].locals || {});

/***/ }),

/***/ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue":
/*!*****************************************************************************!*\
  !*** ./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue ***!
  \*****************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806& */ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806&");
/* harmony import */ var _GlobalInfoFormBlockComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _GlobalInfoFormBlockComponent_vue_vue_type_style_index_0_id_e3381806_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& */ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");



;


/* normalize component */

var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _GlobalInfoFormBlockComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__.render,
  _GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/items/GlobalInfoFormBlockComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue":
/*!*************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue ***!
  \*************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=template&id=1204048e& */ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&");
/* harmony import */ var _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.render,
  _LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************!*\
  !*** ./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806&":
/*!************************************************************************************************************!*\
  !*** ./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806& ***!
  \************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_template_id_e3381806___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=template&id=e3381806&");


/***/ }),

/***/ "./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&":
/*!********************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e& ***!
  \********************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_LocaleDataFormComponent_vue_vue_type_template_id_1204048e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./LocaleDataFormComponent.vue?vue&type=template&id=1204048e& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/articles/forms/add_forms/LocaleDataFormComponent.vue?vue&type=template&id=1204048e&");


/***/ }),

/***/ "./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&":
/*!**************************************************************************************************************************!*\
  !*** ./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& ***!
  \**************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_laravel_mix_node_modules_css_loader_dist_cjs_js_clonedRuleSet_9_use_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_dist_cjs_js_clonedRuleSet_9_use_2_node_modules_vue_loader_lib_index_js_vue_loader_options_GlobalInfoFormBlockComponent_vue_vue_type_style_index_0_id_e3381806_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/style-loader/dist/cjs.js!../../../../../node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!../../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../../node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/laravel-mix/node_modules/css-loader/dist/cjs.js??clonedRuleSet-9.use[1]!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/dist/cjs.js??clonedRuleSet-9.use[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/GlobalInfoFormBlockComponent.vue?vue&type=style&index=0&id=e3381806&lang=css&");


/***/ })

}]);
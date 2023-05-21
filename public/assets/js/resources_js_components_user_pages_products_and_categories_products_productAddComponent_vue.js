"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_products_and_categories_products_productAddComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  props: ['back_url'],
  data: function data() {
    return {
      tab_num: 1,
      categories: [],
      editorConfig: {
        us_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        us_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        us_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ru_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_short_description_text_editor: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ka_text_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_info_editor_config: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config()
      },
      data: [],
      // data: {
      //     global_data: {
      //         us_title_for_url_title: '',

      //         published: 0,
      //         category_id: "Select category",
      //         material: "",
      //         discount: "",
      //         sale_type: "Custom production",
      //         mead_in_georgia: "",
      //     },

      //     us_data: {
      //         title: "",
      //         short_description: "",
      //         text: "",
      //     },

      //     ka_data: {
      //         title: "",
      //         short_description: "",
      //         text: "",
      //     },

      //     ru_data: {
      //         title: "",
      //         short_description: "",
      //         text: "",
      //     }
      // },

      is_loading: false,
      myModal: false
    };
  },
  mounted: function mounted() {
    this.get_product_category_data();
  },
  methods: {
    showModal: function showModal() {
      this.myModal = !this.myModal;
    },
    add_product: function add_product() {
      var _this = this;
      this.data.global_product.us_title_for_url_title = this.data.us_product.title;
      axios.post('/product/add_product/', {
        data: this.data
      }).then(function (response) {
        if (confirm('Do you want send notification about editing article?')) {
          _this.sand_notification();
        } else {
          _this.go_back(true);
        }
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this.us_article_error = error.response.data.errors;
        }
      });
    },
    sand_notification: function sand_notification() {
      var _this2 = this;
      this.is_loading = true;
      axios.post('/user/notifications/send_product_adding_notification').then(function (response) {
        _this2.go_back(true);
      })["catch"](function (err) {
        console.log(err);
      })["finally"](function () {
        return _this2.is_loading = false;
      });
    },
    get_product_category_data: function get_product_category_data() {
      var _this3 = this;
      this.is_loading = true;
      axios.get("/product_category/").then(function (response) {
        _this3.categories = response.data;
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](function () {
        return _this3.is_loading = false;
      });
      ;
    },
    go_back: function go_back() {
      var back_action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      if (back_action == false) {
        if (confirm('Are you sure, you want go back?')) {
          this.$router.go(-1);
        }
      } else {
        this.$router.go(-1);
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78&":
/*!*********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78& ***!
  \*********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, [_c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.is_loading,
      expression: "is_loading"
    }],
    staticClass: "row justify-content-center"
  }, [_c("div", {
    staticClass: "col-md-4"
  }, [_c("img", {
    attrs: {
      src: "../../../../../../public/images/site_img/loading.gif",
      alt: "loading"
    }
  })])]), _vm._v(" "), _c("div", {
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
  }, [_vm._v("Beck")])])]), _vm._v(" "), _c("div", {
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
        return _vm.add_product();
      }
    }
  }, [_vm._v("Save")])])]), _vm._v(" "), _vm.error.length != 0 ? _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_vm.error.us_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English title - " + _vm._s(_vm.error.us_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.us_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English description - " + _vm._s(_vm.error.us_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.us_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                English text - " + _vm._s(_vm.error.us_info_validation.text[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ka_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian title - " + _vm._s(_vm.error.ka_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ka_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian description - " + _vm._s(_vm.error.ka_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ka_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Georgian text - " + _vm._s(_vm.error.ka_info_validation.text[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ru_info_validation.title ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russion title - " + _vm._s(_vm.error.ru_info_validation.title[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ru_info_validation.short_description ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russiondescription - " + _vm._s(_vm.error.ru_info_validation.short_description[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.ru_info_validation.text ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Russion text - " + _vm._s(_vm.error.ru_info_validation.text[0]) + "\n            ")]) : _vm._e()])]) : _vm._e(), _vm._v(" "), _c("div", {
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
  }, [_vm._m(0), _vm._v(" "), _c("form", {
    ref: "myForm",
    staticClass: "width_100",
    staticStyle: {
      "margin-top": "5%"
    },
    attrs: {
      name: "contact-form",
      method: "POST",
      id: "global_form",
      enctyp: "multipart/form-data"
    }
  }, [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Publish ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.published,
      expression: "data.global_product.published"
    }],
    staticClass: "form-control",
    attrs: {
      name: "published"
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data.global_product, "published", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "0"
    }
  }, [_vm._v("Not public")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "1"
    }
  }, [_vm._v("Public")])])])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Sale type ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.sale_type,
      expression: "data.global_product.sale_type"
    }],
    staticClass: "form-control",
    attrs: {
      name: "published"
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data.global_product, "sale_type", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      value: "custom production"
    }
  }, [_vm._v("Custom production")]), _vm._v(" "), _c("option", {
    attrs: {
      value: "online order"
    }
  }, [_vm._v("Online order")])])])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Mead in Georgia ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.mead_in_georgia,
      expression: "data.global_product.mead_in_georgia"
    }],
    attrs: {
      type: "checkbox",
      id: "scales",
      name: "scales"
    },
    domProps: {
      checked: Array.isArray(_vm.data.global_product.mead_in_georgia) ? _vm._i(_vm.data.global_product.mead_in_georgia, null) > -1 : _vm.data.global_product.mead_in_georgia
    },
    on: {
      change: function change($event) {
        var $$a = _vm.data.global_product.mead_in_georgia,
          $$el = $event.target,
          $$c = $$el.checked ? true : false;
        if (Array.isArray($$a)) {
          var $$v = null,
            $$i = _vm._i($$a, $$v);
          if ($$el.checked) {
            $$i < 0 && _vm.$set(_vm.data.global_product, "mead_in_georgia", $$a.concat([$$v]));
          } else {
            $$i > -1 && _vm.$set(_vm.data.global_product, "mead_in_georgia", $$a.slice(0, $$i).concat($$a.slice($$i + 1)));
          }
        } else {
          _vm.$set(_vm.data.global_product, "mead_in_georgia", $$c);
        }
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" discount (%) ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.discount,
      expression: "data.global_product.discount"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "discount"
    },
    domProps: {
      value: _vm.data.global_product.discount
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_product, "discount", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" material")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.material,
      expression: "data.global_product.material"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "material"
    },
    domProps: {
      value: _vm.data.global_product.material
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_product, "material", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Category ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_product.category_id,
      expression: "data.global_product.category_id"
    }],
    staticClass: "form-control",
    attrs: {
      name: "category_id"
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data.global_product, "category_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      disabled: ""
    }
  }, [_vm._v("Select category")]), _vm._v(" "), _vm._l(_vm.categories, function (cat) {
    return _c("option", {
      key: cat.id,
      domProps: {
        value: cat.id
      }
    }, [_vm._v(" " + _vm._s(cat.us_name))]);
  })], 2)])])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 2,
      expression: "tab_num == 2"
    }],
    staticClass: "row"
  }, [_vm._m(1), _vm._v(" "), _c("form", {
    staticClass: "width_100",
    staticStyle: {
      "margin-top": "5%"
    },
    attrs: {
      name: "contact-form",
      method: "POST",
      enctyp: "multipart/form-data"
    }
  }, [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.us_product.title,
      expression: "data.us_product.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.us_product.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.us_product, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Short description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.us_short_description_text_editor
    },
    model: {
      value: _vm.data.us_product.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_product, "short_description", $$v);
      },
      expression: "data.us_product.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.us_text_editor_config
    },
    model: {
      value: _vm.data.us_product.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_product, "text", $$v);
      },
      expression: "data.us_product.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 3,
      expression: "tab_num == 3"
    }],
    staticClass: "row"
  }, [_vm._m(2), _vm._v(" "), _c("form", {
    staticClass: "width_100",
    staticStyle: {
      "margin-top": "5%"
    },
    attrs: {
      name: "contact-form",
      method: "POST",
      enctyp: "multipart/form-data"
    }
  }, [_c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Title ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.ru_product.title,
      expression: "data.ru_product.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "title"
    },
    domProps: {
      value: _vm.data.ru_product.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ru_product, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Short description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.ru_short_description_text_editor
    },
    model: {
      value: _vm.data.ru_product.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_product, "short_description", $$v);
      },
      expression: "data.ru_product.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.ru_text_editor_config
    },
    model: {
      value: _vm.data.ru_product.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_product, "text", $$v);
      },
      expression: "data.ru_product.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 4,
      expression: "tab_num == 4"
    }],
    staticClass: "row"
  }, [_vm._m(3), _vm._v(" "), _c("form", {
    staticClass: "width_100",
    staticStyle: {
      "margin-top": "5%"
    },
    attrs: {
      name: "contact-form",
      method: "POST",
      enctyp: "multipart/form-data"
    },
    on: {
      submit: function submit($event) {
        $event.preventDefault();
        return _vm.add_ka_article.apply(null, arguments);
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
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.ka_product.title,
      expression: "data.ka_product.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "value name"
    },
    domProps: {
      value: _vm.data.ka_product.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ka_product, "title", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Short description ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.ka_short_description_text_editor
    },
    model: {
      value: _vm.data.ka_product.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_product, "short_description", $$v);
      },
      expression: "data.ka_product.short_description"
    }
  })], 1)]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editorConfig.ka_text_editor_config
    },
    model: {
      value: _vm.data.ka_product.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_product, "text", $$v);
      },
      expression: "data.ka_product.text"
    }
  })], 1)])])])])])]);
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
  }, [_c("span", [_vm._v("Region global information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Region global information.")])])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Product english version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Article english version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Product rusion version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Article rusion version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Product georgian version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Article georgian version for site localisation.")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./productAddComponent.vue?vue&type=template&id=bf512b78& */ "./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78&");
/* harmony import */ var _productAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./productAddComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _productAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__.render,
  _productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productAddComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_productAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78&":
/*!************************************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78& ***!
  \************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_productAddComponent_vue_vue_type_template_id_bf512b78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./productAddComponent.vue?vue&type=template&id=bf512b78& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/products_and_categories/products/productAddComponent.vue?vue&type=template&id=bf512b78&");


/***/ })

}]);
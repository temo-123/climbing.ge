"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_tour_tour_AddTourPage_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
// import { editor_config } from '../../../../../mixins/editor/editor_config_mixin.js'
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  // mixins: [
  //     editor_config
  // ],
  props: ['errors_prop'],
  data: function data() {
    return {
      myModal: false
    };
  },
  mounted: function mounted() {
    // this.get_product_category_data()
  },
  methods: {
    showModal: function showModal() {
      this.myModal = !this.myModal;
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");
/* harmony import */ var _items_validator_alerts_component_vue__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../items/validator_alerts_component.vue */ "./resources/js/components/user/items/validator_alerts_component.vue");


/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  components: {
    validator_alerts_component: _items_validator_alerts_component_vue__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  props: ['back_url'],
  data: function data() {
    return {
      tab_num: 1,
      tour_images: [],
      editorConfig: '',
      is_loading: false,
      editor_config: {
        us_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        us_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ru_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ru_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        ka_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ka_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config()
      },
      data: {
        global_tour: {
          // us_title_for_url_title: '',

          category_id: '',
          location: '',
          duration: '',
          min_price: '',
          published: 0
        },
        us_tour: {
          title: "",
          short_description: "",
          text: ""
        },
        ka_tour: {
          title: "",
          short_description: "",
          text: ""
        },
        ru_tour: {
          title: "",
          short_description: "",
          text: ""
        }
      },
      categories: [],
      myModal: false,
      error: []
    };
  },
  mounted: function mounted() {
    this.get_tour_category();
    document.querySelector('body').style.marginLeft = '0';
    document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
  },
  methods: {
    onFileChange: function onFileChange(event, item_id) {
      var image = event.target.files[0];
      var id = item_id - 1;
      this.tour_images[id]['image'] = image;
    },
    add_tour_new_image_value: function add_tour_new_image_value() {
      var new_item_id = this.tour_images.length + 1;
      this.tour_images.push({
        id: new_item_id,
        image: ''
      });
    },
    del_tour_image: function del_tour_image(id) {
      this.removeObjectWithId(this.tour_images, id);
    },
    removeObjectWithId: function removeObjectWithId(arr, id) {
      var objWithIdIndex = arr.findIndex(function (obj) {
        return obj.id === id;
      });
      arr.splice(objWithIdIndex, 1);
      return arr;
    },
    add_tour: function add_tour() {
      var _this = this;
      this.is_loading = true;

      // this.data.global_tour.us_title_for_url_title = this.data.us_tour.title
      var formData = new FormData();
      var loop_num = 0;
      this.tour_images.forEach(function (image) {
        formData.append('tour_images[' + loop_num + ']', image.image);
        loop_num++;
      });
      loop_num = 0;
      formData.append('data', JSON.stringify(this.data));
      axios.post('/tour/add_tour', formData).then(function (response) {
        _this.go_back(true);
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this.error = error.response.data.validation;
        }
      })["finally"](this.is_loading = false);
    },
    get_tour_category: function get_tour_category() {
      var _this2 = this;
      axios.get("/tour/category/get_all_categories/").then(function (response) {
        _this2.categories = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    go_back: function go_back() {
      var action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
      this.is_back_action_query = this.$going.back(this, action);
    }
  }
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c&":
/*!*******************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c& ***!
  \*******************************************************************************************************************************************************************************************************************************************************************************************************************/
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
  }, _vm._l(_vm.errors_prop, function (error, big_name) {
    return _c("span", {
      key: big_name,
      attrs: {
        role: "alert"
      }
    }, _vm._l(error, function (breed, name) {
      return _c("div", {
        key: name,
        staticClass: "alert alert-danger"
      }, [_vm._v("\n            " + _vm._s(big_name) + " -> " + _vm._s(name) + " -> " + _vm._s(breed[0]) + "\n        ")]);
    }), 0);
  }), 0);
};
var staticRenderFns = [];
render._withStripped = true;


/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78&":
/*!**************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78& ***!
  \**************************************************************************************************************************************************************************************************************************************************************************************************************/
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
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
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
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
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
        return _vm.add_tour();
      }
    }
  }, [_vm._v("Save")])])]), _vm._v(" "), _vm.error.length != 0 ? _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
    staticClass: "row"
  }, [_c("validator_alerts_component", {
    attrs: {
      errors_prop: _vm.error
    }
  })], 1) : _vm._e(), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
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
      value: _vm.data.global_tour.published,
      expression: "data.global_tour.published"
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
        _vm.$set(_vm.data.global_tour, "published", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
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
      "for": "category"
    }
  }, [_vm._v(" Category ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-10"
  }, [_c("select", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_tour.category_id,
      expression: "data.global_tour.category_id"
    }],
    staticClass: "form-control",
    attrs: {
      name: "category"
    },
    on: {
      change: function change($event) {
        var $$selectedVal = Array.prototype.filter.call($event.target.options, function (o) {
          return o.selected;
        }).map(function (o) {
          var val = "_value" in o ? o._value : o.value;
          return val;
        });
        _vm.$set(_vm.data.global_tour, "category_id", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
      }
    }
  }, [_c("option", {
    attrs: {
      disabled: ""
    },
    domProps: {
      value: ""
    }
  }, [_vm._v("Select category")]), _vm._v(" "), _vm._l(_vm.categories, function (category) {
    return _c("option", {
      key: category.id,
      domProps: {
        value: category.id
      }
    }, [_vm._v(_vm._s(category.us_name))]);
  })], 2)])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Location ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_tour.location,
      expression: "data.global_tour.location"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.global_tour.location
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_tour, "location", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Duration ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_tour.duration,
      expression: "data.global_tour.duration"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.global_tour.duration
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_tour, "duration", $event.target.value);
      }
    }
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Min price ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    directives: [{
      name: "model",
      rawName: "v-model",
      value: _vm.data.global_tour.min_price,
      expression: "data.global_tour.min_price"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.global_tour.min_price
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.global_tour, "min_price", $event.target.value);
      }
    }
  })])])]), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-groupe"
  }, [_vm.tour_images.length < 8 ? _c("button", {
    staticClass: "btn btn-primary float-left",
    on: {
      click: function click($event) {
        return _vm.add_tour_new_image_value();
      }
    }
  }, [_vm._v("Add new tour image")]) : _vm._e()])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("table", {
    staticClass: "table table-hover",
    attrs: {
      id: "dev-table"
    }
  }, [_vm._m(1), _vm._v(" "), _c("tbody", _vm._l(_vm.tour_images, function (tour_image) {
    return _c("tr", {
      key: tour_image.id
    }, [_c("td", [_c("form", {
      ref: "myForm",
      refInFor: true
    }, [_c("input", {
      attrs: {
        type: "file",
        name: "image",
        id: "image"
      },
      on: {
        change: function change($event) {
          return _vm.onFileChange($event, tour_image.id);
        }
      }
    })])]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_c("button", {
      staticClass: "btn btn-danger",
      on: {
        click: function click($event) {
          return _vm.del_tour_image(tour_image.id);
        }
      }
    }, [_vm._v("Delete")])])]);
  }), 0)])])])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 2,
      expression: "tab_num == 2"
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
      value: _vm.data.us_tour.title,
      expression: "data.us_tour.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "name"
    },
    domProps: {
      value: _vm.data.us_tour.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.us_tour, "title", $event.target.value);
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
      config: _vm.editor_config.us_short_description_text
    },
    model: {
      value: _vm.data.us_tour.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_tour, "short_description", $$v);
      },
      expression: "data.us_tour.short_description"
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
      config: _vm.editor_config.us_text
    },
    model: {
      value: _vm.data.us_tour.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_tour, "text", $$v);
      },
      expression: "data.us_tour.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 3,
      expression: "tab_num == 3"
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
      value: _vm.data.ru_tour.title,
      expression: "data.ru_tour.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "title"
    },
    domProps: {
      value: _vm.data.ru_tour.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ru_tour, "title", $event.target.value);
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
      config: _vm.editor_config.ru_short_description_text
    },
    model: {
      value: _vm.data.ru_tour.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_tour, "short_description", $$v);
      },
      expression: "data.ru_tour.short_description"
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
      config: _vm.editor_config.ru_text
    },
    model: {
      value: _vm.data.ru_tour.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_tour, "text", $$v);
      },
      expression: "data.ru_tour.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 4,
      expression: "tab_num == 4"
    }],
    staticClass: "row"
  }, [_vm._m(4), _vm._v(" "), _c("form", {
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
      value: _vm.data.ka_tour.title,
      expression: "data.ka_tour.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "value name"
    },
    domProps: {
      value: _vm.data.ka_tour.title
    },
    on: {
      input: function input($event) {
        if ($event.target.composing) return;
        _vm.$set(_vm.data.ka_tour, "title", $event.target.value);
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
      config: _vm.editor_config.ka_short_description_text
    },
    model: {
      value: _vm.data.ka_tour.short_description,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_tour, "short_description", $$v);
      },
      expression: "data.ka_tour.short_description"
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
      config: _vm.editor_config.ka_text
    },
    model: {
      value: _vm.data.ka_tour.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_tour, "text", $$v);
      },
      expression: "data.ka_tour.text"
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
  }, [_c("span", [_vm._v("Tour global information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Tour global information.")])])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("thead", [_c("tr", [_c("th", [_vm._v("Image")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("Delite")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Tour english version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Tour english version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Tour rusion version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Tour rusion version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Tour georgian version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Tour georgian version for site localisation.")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/items/validator_alerts_component.vue":
/*!***************************************************************************!*\
  !*** ./resources/js/components/user/items/validator_alerts_component.vue ***!
  \***************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./validator_alerts_component.vue?vue&type=template&id=e5381b4c& */ "./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c&");
/* harmony import */ var _validator_alerts_component_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./validator_alerts_component.vue?vue&type=script&lang=js& */ "./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _validator_alerts_component_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__.render,
  _validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/items/validator_alerts_component.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/tour/tour/AddTourPage.vue":
/*!**********************************************************************!*\
  !*** ./resources/js/components/user/pages/tour/tour/AddTourPage.vue ***!
  \**********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AddTourPage.vue?vue&type=template&id=90df9c78& */ "./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78&");
/* harmony import */ var _AddTourPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AddTourPage.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AddTourPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__.render,
  _AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/tour/tour/AddTourPage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************!*\
  !*** ./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_validator_alerts_component_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./validator_alerts_component.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_validator_alerts_component_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js&":
/*!***********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddTourPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AddTourPage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AddTourPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c&":
/*!**********************************************************************************************************!*\
  !*** ./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c& ***!
  \**********************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_validator_alerts_component_vue_vue_type_template_id_e5381b4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./validator_alerts_component.vue?vue&type=template&id=e5381b4c& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/items/validator_alerts_component.vue?vue&type=template&id=e5381b4c&");


/***/ }),

/***/ "./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78&":
/*!*****************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78& ***!
  \*****************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_AddTourPage_vue_vue_type_template_id_90df9c78___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./AddTourPage.vue?vue&type=template&id=90df9c78& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/tour/tour/AddTourPage.vue?vue&type=template&id=90df9c78&");


/***/ })

}]);
"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_toure_EditTourePage_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../mixins/editor/editor_config_mixin.js */ "./resources/js/mixins/editor/editor_config_mixin.js");

/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  mixins: [_mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config],
  props: [
    // 'back_url',
  ],
  data: function data() {
    return {
      tab_num: 1,
      tore_new_images: [],
      tore_old_images: [],
      // regions: [],

      error: [],
      is_loading: false,
      editor_config: {
        us_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        us_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        // us_info: editor_config.get_big_editor_config(),

        ru_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ru_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config(),
        // ru_info: editor_config.get_big_editor_config(),

        ka_short_description_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_small_editor_config(),
        ka_text: _mixins_editor_editor_config_mixin_js__WEBPACK_IMPORTED_MODULE_0__.editor_config.get_big_editor_config()
        // ka_info: editor_config.get_big_editor_config(),
      },

      data: {
        global_data: {},
        us_data: {},
        ka_data: {},
        ru_data: {}
      },
      // the_date: moment().format('YYYY-MM-DD'),

      change_url_title: false
    };
  },
  mounted: function mounted() {
    this.get_editing_tore_data();
    // this.get_region_data()

    document.querySelector('body').style.marginLeft = '0';
    document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
  },
  methods: {
    // get_region_data: function () {
    //     axios
    //         .get("../../api/article/")
    //         .then((response) => {
    //             this.regions = response.data;
    //         })
    //         .catch((error) => console.log(error));
    // },
    onFileChange: function onFileChange(event, item_id) {
      var image = event.target.files[0];
      var id = item_id - 1;
      this.tore_new_images[id]['image'] = image;
    },
    add_tore_new_image_value: function add_tore_new_image_value() {
      if (this.tore_old_images) {
        if (this.tore_new_images.length + this.tore_old_images.length < 8) {
          var new_item_id = this.tore_new_images.length + 1;
          this.tore_new_images.push({
            id: new_item_id,
            image: ''
          });
        }
      } else {
        if (this.tore_new_images.length < 8) {
          var new_item_id = this.tore_new_images.length + 1;
          this.tore_new_images.push({
            id: new_item_id,
            image: ''
          });
        }
      }
    },
    get_editing_tore_data: function get_editing_tore_data() {
      var _this = this;
      this.data_for_tab = [];
      this.is_loading = true;
      axios.get("../../api/tore/get_editing_tore/" + this.$route.params.id).then(function (response) {
        _this.editing_data = response.data;
        _this.data = {
          global_data: response.data.global_tore,
          us_data: response.data.us_tore,
          ru_data: response.data.ru_tore,
          ka_data: response.data.ka_tore
        };
        _this.tore_old_images = response.data.tore_images;
        _this.get_tore_images();

        // if(this.data.global_data.published_data != null){
        //     this.the_date = this.data.global_data.published_data
        // }
      })["catch"](function (error) {
        return console.log(error);
      })["finally"](this.is_loading = false);
    },
    get_tore_images: function get_tore_images() {
      var _this2 = this;
      this.data_for_tab = [];
      axios.get("../../api/tore/get_tore_images/" + this.$route.params.id).then(function (response) {
        _this2.tore_old_images = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    del_tore_image_from_db: function del_tore_image_from_db(image_id) {
      var _this3 = this;
      if (confirm('Are you sure, you want delite this image?')) {
        axios["delete"]("../../../api/tore/del_tore_image/" + image_id).then(function (response) {
          _this3.get_tore_images();
        })["catch"](function (error) {
          return console.log(error);
        });
      }
    },
    del_tore_image: function del_tore_image(id) {
      this.removeObjectWithId(this.tore_new_images, id);
    },
    removeObjectWithId: function removeObjectWithId(arr, id) {
      var objWithIdIndex = arr.findIndex(function (obj) {
        return obj.id === id;
      });
      arr.splice(objWithIdIndex, 1);
      return arr;
    },
    change_url_title_in_global_tore: function change_url_title_in_global_tore() {
      if (!this.change_url_title) {
        if (confirm('Are you sure, you want change URL title? It vhile bad for SEO potimization')) {
          this.change_url_title = true;
        }
      } else {
        this.change_url_title = false;
      }
    },
    edit_tore: function edit_tore() {
      var _this4 = this;
      this.is_loading = true;
      if (this.change_url_title) {
        this.data.global_data.change_url_title = this.change_url_title;
        this.data.global_data.us_title_for_url_title = this.data.us_data.title;
      } else {
        this.data.global_data.change_url_title = false;
        this.data.global_data.us_title_for_url_title = false;
      }
      var formData = new FormData();
      var loop_num = 0;
      this.tore_new_images.forEach(function (image) {
        formData.append('tore_new_images[' + loop_num + ']', image.image);
        loop_num++;
      });
      loop_num = 0;
      formData.append('data', JSON.stringify(this.data));
      axios.post('../../api/tore/edit_tore/' + this.$route.params.id, formData).then(function (response) {
        _this4.go_back(true);
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this4.error = error.response.data.validation;
        } else {
          alert(error);
        }
      })["finally"](this.is_loading = false);
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

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c&":
/*!************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c& ***!
  \************************************************************************************************************************************************************************************************************************************************************************************************************/
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
        return _vm.edit_tore();
      }
    }
  }, [_vm._v("Save update")])])]), _vm._v(" "), _vm.error.length != 0 ? _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: !_vm.is_loading,
      expression: "!is_loading"
    }],
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_vm.error.global_info_validation.published ? _c("div", {
    staticClass: "alert alert-danger",
    attrs: {
      role: "alert"
    }
  }, [_vm._v("\n                Published - " + _vm._s(_vm.error.global_info_validation.published[0]) + "\n            ")]) : _vm._e(), _vm._v(" "), _vm.error.us_info_validation.title ? _c("div", {
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
      value: _vm.data.global_data.published,
      expression: "data.global_data.published"
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
        _vm.$set(_vm.data.global_data, "published", $event.target.multiple ? $$selectedVal : $$selectedVal[0]);
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
  }, [_vm._v("Public")])])])])]), _vm._v(" "), _vm._m(1), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("table", {
    staticClass: "table table-hover",
    attrs: {
      id: "dev-table"
    }
  }, [_vm._m(2), _vm._v(" "), _c("tbody", _vm._l(_vm.tore_old_images, function (tore_old_image) {
    return _c("tr", {
      key: tore_old_image.id
    }, [_c("td", [_c("img", {
      staticClass: "img-responsive",
      attrs: {
        src: "../../../../images/tore_img/" + tore_old_image.image,
        alt: tore_old_image.title
      }
    })]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_c("button", {
      staticClass: "btn btn-danger",
      on: {
        click: function click($event) {
          return _vm.del_tore_image_from_db(tore_old_image.id);
        }
      }
    }, [_vm._v("Delete")])])]);
  }), 0)])])])]), _vm._v(" "), _vm._m(3), _vm._v(" "), _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-groupe"
  }, [_c("button", {
    staticClass: "btn btn-primary float-left",
    on: {
      click: function click($event) {
        return _vm.add_tore_new_image_value();
      }
    }
  }, [_vm._v("Add new tore imagee")])])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("table", {
    staticClass: "table table-hover",
    attrs: {
      id: "dev-table"
    }
  }, [_vm._m(4), _vm._v(" "), _c("tbody", _vm._l(_vm.tore_new_images, function (tore_image) {
    return _c("tr", {
      key: tore_image.id
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
          return _vm.onFileChange($event, tore_image.id);
        }
      }
    })])]), _vm._v(" "), _c("td", [_vm._v("|")]), _vm._v(" "), _c("td", [_c("button", {
      staticClass: "btn btn-danger",
      on: {
        click: function click($event) {
          return _vm.del_tore_image(tore_image.id);
        }
      }
    }, [_vm._v("Delete")])])]);
  }), 0)])])]), _vm._v(" "), _c("div", {
    staticClass: "row"
  }, [_c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "form-groupe"
  }, [_c("button", {
    staticClass: "btn btn-primary float-left",
    on: {
      click: function click($event) {
        return _vm.add_tore_new_image_value();
      }
    }
  }, [_vm._v("Add new tore imagee")])])])])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 2,
      expression: "tab_num == 2"
    }],
    staticClass: "row"
  }, [_vm._m(5), _vm._v(" "), _c("form", {
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
  })])]), _vm._v(" "), _c("div", {
    staticClass: "form-group clearfix"
  }, [_c("label", {
    staticClass: "col-xs-2 control-label",
    attrs: {
      "for": "name"
    }
  }, [_vm._v(" Change URL title ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("input", {
    attrs: {
      type: "checkbox",
      id: "scales",
      name: "scales"
    },
    on: {
      click: function click($event) {
        return _vm.change_url_title_in_global_tore();
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
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.us_text
    },
    model: {
      value: _vm.data.us_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.us_data, "text", $$v);
      },
      expression: "data.us_data.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 3,
      expression: "tab_num == 3"
    }],
    staticClass: "row"
  }, [_vm._m(6), _vm._v(" "), _c("form", {
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
      value: _vm.data.ru_data.title,
      expression: "data.ru_data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "title"
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
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ru_text
    },
    model: {
      value: _vm.data.ru_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ru_data, "text", $$v);
      },
      expression: "data.ru_data.text"
    }
  })], 1)])])]), _vm._v(" "), _c("div", {
    directives: [{
      name: "show",
      rawName: "v-show",
      value: _vm.tab_num == 4,
      expression: "tab_num == 4"
    }],
    staticClass: "row"
  }, [_vm._m(7), _vm._v(" "), _c("form", {
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
      value: _vm.data.ka_data.title,
      expression: "data.ka_data.title"
    }],
    staticClass: "form-control",
    attrs: {
      type: "text",
      name: "value name"
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
  }, [_vm._v(" text ")]), _vm._v(" "), _c("div", {
    staticClass: "col-xs-8"
  }, [_c("ckeditor", {
    attrs: {
      config: _vm.editor_config.ka_text
    },
    model: {
      value: _vm.data.ka_data.text,
      callback: function callback($$v) {
        _vm.$set(_vm.data.ka_data, "text", $$v);
      },
      expression: "data.ka_data.text"
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
  }, [_c("span", [_vm._v("Toure global information")])]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Toure global information.")])])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_vm._v("\n                        Olredy added\n                    ")])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("thead", [_c("tr", [_c("th", [_vm._v("Image")]), _vm._v(" "), _c("th", [_vm._v("|")]), _vm._v(" "), _c("th", [_vm._v("Delite")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "col-md-12"
  }, [_c("div", {
    staticClass: "row"
  }, [_vm._v("\n                        New images\n                    ")])]);
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
  }, [_vm._v("Toure english version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Toure english version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Toure rusion version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Toure rusion version for site localisation.")])])]);
}, function () {
  var _vm = this,
    _c = _vm._self._c;
  return _c("div", {
    staticClass: "width_100 jumbotron jumbotron-fluid"
  }, [_c("div", {
    staticClass: "container"
  }, [_c("h2", {
    staticClass: "display-4"
  }, [_vm._v("Toure georgian version")]), _vm._v(" "), _c("p", {
    staticClass: "lead"
  }, [_vm._v("Toure georgian version for site localisation.")])])]);
}];
render._withStripped = true;


/***/ }),

/***/ "./resources/js/components/user/pages/toure/EditTourePage.vue":
/*!********************************************************************!*\
  !*** ./resources/js/components/user/pages/toure/EditTourePage.vue ***!
  \********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditTourePage.vue?vue&type=template&id=5e0ba92c& */ "./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c&");
/* harmony import */ var _EditTourePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditTourePage.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EditTourePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__.render,
  _EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/toure/EditTourePage.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************!*\
  !*** ./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditTourePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EditTourePage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditTourePage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c& ***!
  \***************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_use_0_node_modules_vue_loader_lib_loaders_templateLoader_js_ruleSet_1_rules_2_node_modules_vue_loader_lib_index_js_vue_loader_options_EditTourePage_vue_vue_type_template_id_5e0ba92c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./EditTourePage.vue?vue&type=template&id=5e0ba92c& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5.use[0]!./node_modules/vue-loader/lib/loaders/templateLoader.js??ruleSet[1].rules[2]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/toure/EditTourePage.vue?vue&type=template&id=5e0ba92c&");


/***/ })

}]);
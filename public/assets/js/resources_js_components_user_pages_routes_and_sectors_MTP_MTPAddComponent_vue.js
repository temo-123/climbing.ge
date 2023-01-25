"use strict";
(self["webpackChunkclimbing_loc"] = self["webpackChunkclimbing_loc"] || []).push([["resources_js_components_user_pages_routes_and_sectors_MTP_MTPAddComponent_vue"],{

/***/ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = ({
  data: function data() {
    return {
      // sellected: '',
      // route_type: '',
      regions: [],
      sectors: [],
      errors: [],
      // sector_id: "",
      // title: "",
      // text: "",
      // height: "",
      // height: "",
      data: {
        article_id: "",
        sector_id: "",
        name: "",
        text: "",
        height: "",
        first_ascent: "",
        author: ''
      },
      // published: "",
      go_back_action: false
    };
  },
  mounted: function mounted() {
    this.get_region_data();
    this.get_sectors_data();
  },
  methods: {
    get_region_data: function get_region_data() {
      var _this = this;

      axios.post("../../api/article/", {
        category: 'outdoor'
      }).then(function (response) {
        _this.regions = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    get_sectors_data: function get_sectors_data() {
      var _this2 = this;

      axios.get("../../api/sector/").then(function (response) {
        _this2.all_sectors = response.data;
      })["catch"](function (error) {
        return console.log(error);
      });
    },
    filter_sectors: function filter_sectors() {
      var vm = this;
      this.sectors = this.all_sectors.filter(function (item) {
        return item.article_id == vm.data.article_id;
      });
    },
    add_mtp: function add_mtp() {
      var _this3 = this;

      axios.post('../api/mtp/mtp_add', {
        data: this.data
      }).then(function (response) {
        if (!_this3.go_back_action) {
          alert('Saving completed');

          _this3.clear_form();
        } else {
          _this3.go_back(true);
        }
      })["catch"](function (error) {
        if (error.response.status == 422) {
          _this3.errors = error.response.data.errors;
        }
      });
    },
    clear_form: function clear_form() {
      this.data = {
        article_id: this.data.article_id,
        sector_id: this.data.sector_id,
        name: "",
        text: "",
        height: "",
        first_ascent: "",
        author: ''
      };
    },
    go_back: function go_back() {
      var back_action = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;

      if (back_action == false) {
        if (confirm('Are you sure, you want go back?')) {
          this.$router.push({
            name: 'routeAndSectorList'
          });
        }
      } else {
        this.$router.push({
          name: 'routeAndSectorList'
        });
      }
    }
  }
});

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue":
/*!***************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue ***!
  \***************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./MTPAddComponent.vue?vue&type=template&id=f28236d8& */ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8&");
/* harmony import */ var _MTPAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./MTPAddComponent.vue?vue&type=script&lang=js& */ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! !../../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */
;
var component = (0,_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _MTPAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__.render,
  _MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns,
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue"
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (component.exports);

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MTPAddComponent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js??clonedRuleSet-5[0].rules[0].use[0]!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=script&lang=js&");
 /* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (_node_modules_babel_loader_lib_index_js_clonedRuleSet_5_0_rules_0_use_0_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPAddComponent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8&":
/*!**********************************************************************************************************************!*\
  !*** ./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8& ***!
  \**********************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__.render),
/* harmony export */   "staticRenderFns": () => (/* reexport safe */ _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__.staticRenderFns)
/* harmony export */ });
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_MTPAddComponent_vue_vue_type_template_id_f28236d8___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../../node_modules/vue-loader/lib/index.js??vue-loader-options!./MTPAddComponent.vue?vue&type=template&id=f28236d8& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8&");


/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8&":
/*!*************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib/index.js??vue-loader-options!./resources/js/components/user/pages/routes_and_sectors/MTP/MTPAddComponent.vue?vue&type=template&id=f28236d8& ***!
  \*************************************************************************************************************************************************************************************************************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "render": () => (/* binding */ render),
/* harmony export */   "staticRenderFns": () => (/* binding */ staticRenderFns)
/* harmony export */ });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "col-md-12" }, [
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { type: "submit" },
            on: {
              click: function ($event) {
                return _vm.go_back()
              },
            },
          },
          [_vm._v("Beck")]
        ),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { form: "route_add_form", type: "submit" },
            on: {
              click: function ($event) {
                _vm.go_back_action = true
              },
            },
          },
          [_vm._v("Save and go back")]
        ),
        _vm._v(" "),
        _c("p", [_vm._v("Save and go to route tab page")]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "row" }, [
      _c("div", { staticClass: "form-group" }, [
        _c(
          "button",
          {
            staticClass: "btn btn-primary",
            attrs: { form: "route_add_form", type: "submit" },
            on: {
              click: function ($event) {
                _vm.go_back_action = false
              },
            },
          },
          [_vm._v("Save and add more reoute")]
        ),
        _vm._v(" "),
        _c("p", [_vm._v("Save and add more route")]),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "wrapper container-fluid container" }, [
      _c(
        "form",
        {
          attrs: { id: "route_add_form" },
          on: {
            submit: function ($event) {
              $event.preventDefault()
              return _vm.add_mtp()
            },
          },
        },
        [
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Region ")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-5" }, [
              _c(
                "select",
                {
                  directives: [
                    {
                      name: "model",
                      rawName: "v-model",
                      value: _vm.data.article_id,
                      expression: "data.article_id",
                    },
                  ],
                  staticClass: "form-control",
                  attrs: { required: "" },
                  on: {
                    click: function ($event) {
                      return _vm.filter_sectors()
                    },
                    change: function ($event) {
                      var $$selectedVal = Array.prototype.filter
                        .call($event.target.options, function (o) {
                          return o.selected
                        })
                        .map(function (o) {
                          var val = "_value" in o ? o._value : o.value
                          return val
                        })
                      _vm.$set(
                        _vm.data,
                        "article_id",
                        $event.target.multiple
                          ? $$selectedVal
                          : $$selectedVal[0]
                      )
                    },
                  },
                },
                [
                  _c("option", { attrs: { value: "", disabled: "" } }, [
                    _vm._v("Select outdoor article"),
                  ]),
                  _vm._v(" "),
                  _vm._l(_vm.regions, function (region) {
                    return _c(
                      "option",
                      { key: region.id, domProps: { value: region.id } },
                      [_vm._v(_vm._s(region.url_title))]
                    )
                  }),
                ],
                2
              ),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-5" }, [
              _vm.data.article_id != ""
                ? _c(
                    "select",
                    {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.data.sector_id,
                          expression: "data.sector_id",
                        },
                      ],
                      staticClass: "form-control",
                      attrs: { required: "" },
                      on: {
                        change: function ($event) {
                          var $$selectedVal = Array.prototype.filter
                            .call($event.target.options, function (o) {
                              return o.selected
                            })
                            .map(function (o) {
                              var val = "_value" in o ? o._value : o.value
                              return val
                            })
                          _vm.$set(
                            _vm.data,
                            "sector_id",
                            $event.target.multiple
                              ? $$selectedVal
                              : $$selectedVal[0]
                          )
                        },
                      },
                    },
                    [
                      _c("option", { attrs: { value: "", disabled: "" } }, [
                        _vm._v("Select sector"),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.sectors, function (sector) {
                        return _c(
                          "option",
                          { key: sector.id, domProps: { value: sector.id } },
                          [_vm._v(_vm._s(sector.name))]
                        )
                      }),
                    ],
                    2
                  )
                : _vm._e(),
            ]),
          ]),
          _vm._v(" "),
          _vm.errors.sector_id
            ? _c("div", { staticClass: "form-group clearfix row" }, [
                _c("div", { staticClass: "col-md-12" }, [
                  _c(
                    "div",
                    {
                      staticClass: "alert alert-danger",
                      attrs: { role: "alert" },
                    },
                    [
                      _vm._v(
                        "\n              " +
                          _vm._s(_vm.errors.sector_id[0]) +
                          "\n            "
                      ),
                    ]
                  ),
                ]),
              ])
            : _vm._e(),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Multy pitch name ")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-10" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.data.name,
                    expression: "data.name",
                  },
                ],
                staticClass: "form-control",
                attrs: {
                  type: "text",
                  name: "name",
                  placeholder: "Name",
                  required: "",
                },
                domProps: { value: _vm.data.name },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.data, "name", $event.target.value)
                  },
                },
              }),
              _vm._v(" "),
              _vm.errors.name
                ? _c(
                    "div",
                    {
                      staticClass: "alert alert-danger",
                      attrs: { role: "alert" },
                    },
                    [
                      _vm._v(
                        "\n              " +
                          _vm._s(_vm.errors.name[0]) +
                          "\n            "
                      ),
                    ]
                  )
                : _vm._e(),
            ]),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" text ")]
            ),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "col-md-10" },
              [
                _c("ckeditor", {
                  model: {
                    value: _vm.data.text,
                    callback: function ($$v) {
                      _vm.$set(_vm.data, "text", $$v)
                    },
                    expression: "data.text",
                  },
                }),
              ],
              1
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v(" Height ")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-9" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.data.height,
                    expression: "data.height",
                  },
                ],
                staticClass: "form-control",
                attrs: { type: "number", name: "title", placeholder: "Height" },
                domProps: { value: _vm.data.height },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.data, "height", $event.target.value)
                  },
                },
              }),
            ]),
            _vm._v(" "),
            _c(
              "label",
              { staticClass: "col-md-1 control-label", attrs: { for: "name" } },
              [_vm._v(" M ")]
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "form-group clearfix row" }, [
            _c(
              "label",
              { staticClass: "col-md-2 control-label", attrs: { for: "name" } },
              [_vm._v("First ascent / Auther")]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-5" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.data.first_ascent,
                    expression: "data.first_ascent",
                  },
                ],
                staticClass: "form-control",
                attrs: {
                  type: "text",
                  name: "first_ascent",
                  placeholder: "First ascent",
                },
                domProps: { value: _vm.data.first_ascent },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.data, "first_ascent", $event.target.value)
                  },
                },
              }),
            ]),
            _vm._v(" "),
            _c("div", { staticClass: "col-md-5" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.data.author,
                    expression: "data.author",
                  },
                ],
                staticClass: "form-control",
                attrs: { type: "text", name: "author", placeholder: "Auther" },
                domProps: { value: _vm.data.author },
                on: {
                  input: function ($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.$set(_vm.data, "author", $event.target.value)
                  },
                },
              }),
            ]),
          ]),
        ]
      ),
    ]),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ })

}]);